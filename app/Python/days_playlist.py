#!/usr/bin/env python
# coding: utf-8

# In[1]:


import numpy as np
import discogs_client as dc
#import matplotlib.pyplot as plt
from sklearn.mixture import GaussianMixture
#import psycopg2
import mysql.connector
from mysql.connector import Error
import datetime
from time import sleep, time
import yandex_music as ya


# In[2]:


d = dc.Client('ExampleApplication/0.1', user_token="dpLilGmVVAOtjFctLLPkYGnSTZmjGEUqiqzfIwLY")
p_coeffs ={
    'style':0.5,
    'artist':0.5
}


# In[3]:


ya_client=ya.client.Client()


# In[20]:


class User(object):


    def __init__(self, _id, period=7):

        self.years={}
        self.genres={}
        self.styles={}
        self.artists={}
        self.id = _id
        self.__period = period
        self.__playlist = []
        self.history = self.__get_history()


    def __get_history(self):

        cursor.execute('SELECT name, COUNT(*) AS n, author, in_playlist FROM histories where user_id=%s AND updated_at>DATE_SUB(CURDATE(), INTERVAL %s DAY) GROUP BY name, author, in_playlist;',(self.id,self.__period,))
        res = cursor.fetchall()
        return res

    def __fix_name(self, entry):

        if entry['author']!='Unknown':
            entry['name'] = entry['name'].replace(entry['author'],'')[3:]
        else:
            tmp = entry['name'].split(' - ')
            entry['name'] = tmp[1]
            entry['author'] = tmp[0]
        return entry


    def __make_shares(self):

        summ = sum(list(self.genres.values()))
        for genre in self.genres:
            self.genres[genre] = self.genres[genre]/summ
        summ = sum(list(self.styles.values()))
        for style in self.styles:
            self.styles[style] = self.styles[style]/summ
        summ = sum(list(self.artists.values()))
        for artist in self.artists:
            self.artists[artist] = self.artists[artist]/summ
        summ = sum(list(self.years.values()))
        for year in self.years:
            self.years[year] = self.years[year]/summ


    def __get_songs_info(self):

        for entry in self.history:

            entry = self.__fix_name(entry)
            res = d.search(entry['name'], artist=entry['author'], type='release').sort('year')
            sleep(1)
            try:
                year = res[0].year
                if res[0].styles!=None:
                    for style in res[0].styles:
                        if not style in self.styles:
                            self.styles[style] = entry['n']
                        else:
                            self.styles[style] += entry['n']
                if res[0].genres!=None:
                    for genre in res[0].genres:
                        if not genre in self.genres:
                            self.genres[genre] = entry['n']
                        else:
                            self.genres[genre] += entry['n']
                if not year in self.years:
                    self.years[year] =entry['n']
                else:
                    self.years[year] +=entry['n']
                artist = entry['author']
                if not artist in self.artists:
                    self.artists[artist] =entry['n']
                else:
                    self.artists[artist] +=entry['n']
                print("Found song {0} - {1} in database".format(entry['author'], entry['name']))
            except IndexError:
                print("Error: Can't find song {0} - {1} in database".format(entry['author'], entry['name']))
                continue
            except Exception as e:
                print("Unknown error: {0}".format(e))
                continue

    def calc_preferences(self):
        self.__get_songs_info()
        self.__make_shares()

    def pick_song(self, num = 10, max_fails = 3):
        done = 0
        fails = 0 #количество ошибок получения песни подряд
        # если количество попыток превышено, считаем, что песня найдена, чтобы не уйти в бесконечный цикл
        while done < num:

            if fails >= max_fails:
                done += 1

            artist = ''
            style = ''
            genre = ''
            year = ''
            use_artist = True if np.random.uniform(0, 1)<=p_coeffs['artist'] else False
            use_style = True if np.random.uniform(0, 1)<=p_coeffs['artist'] else False

            if use_artist:
                artist = np.random.choice(list(self.artists.keys()), p=list(self.artists.values()))
            else:
                if use_style:
                    style = np.random.choice(list(self.styles.keys()), p=list(self.styles.values()))
                else:
                    genre = np.random.choice(list(self.genres.keys()), p=list(self.genres.values()))
                year = np.random.choice(list(self.years.keys()), p=list(self.years.values()))

            try:
                res = d.search(artist=artist, year=year, genre=genre, style=style, type='master')
                errors = 0
                rand_num = np.random.randint(0, 10 if len(res)>=10 else len(res))
                selected_release = res[rand_num]
                while ('*' in selected_release.title) and (errors<5):
                    sleep(1)
                    errors +=1
                    rand_num = np.random.randint(0, 10 if len(res)>=10 else len(res))
                    selected_release = res[rand_num]
                print(selected_release)

                track_num = np.random.randint(0, len(selected_release.tracklist))
                song = {'title': selected_release.tracklist[track_num].title,
                        'artist': selected_release.main_release.artists[0].name}

            except dc.exceptions.HTTPError:
                print("HTTPError error")
                fails += 1
                sleep(1)
                continue

            except ValueError:
                print("ValueError - can't find a song")
                fails += 1
                continue

            self.__playlist.append(song)
            done +=1
            fails = 0
            sleep(1)

    def get_playlist(self):
        if len(self.__playlist)==0:
            self.pick_song(20)
        return self.__playlist



# In[21]:


try:
    connection = mysql.connector.connect(host='localhost',
                                         database='musario2',
                                         user='root',
                                         password='0864')
    cursor = connection.cursor(dictionary=True)
except:
    print('ERROR in connection to database');


# In[22]:


cursor.execute('SELECT id FROM users;')
res = cursor.fetchall()


# In[23]:


users = []


# In[24]:


for user in res:
    users.append(User(user['id']))


# In[25]:


for user in users:
    user.calc_preferences()
    user.get_playlist()


# In[28]:


try:
    cursor.execute("DELETE FROM everydays WHERE 1;")
    connection.commit()
except Exception as e:
    print('Error: {0}'.format(e))


# In[29]:


for user in users:
    for song in user.get_playlist():
        try:
            song_name = song['artist']+' - ' + song['title']
            ya_res = ya_client.search(text=song['artist']+' - ' + song['title'], type_='track', page=0).tracks.results[0]
            cursor.execute("INSERT INTO everydays (user_id, name, author, ya_id) VALUES (%s, %s, %s, %s);",
                           (str(user.id), song_name, str(song['artist']), str(ya_res['id']),))
            connection.commit()
        except AttributeError as e:
            print('Error getting song ya_id!: AttributeError')
            print(e)
            continue
        except Exception as e:
            print('Error: {0}'.format(e))

