<style>
    .pointed {
        cursor: pointer;
    }

    .font-normal {
        font-style: normal !important;
    }

    /*UI toast*/
    .toastify {
        display: flex;
        flex-direction: column-reverse;
        align-items: flex-end;
        position: fixed;
        right: 15px;
        bottom: 0;
        width: auto;
        height: auto;
        z-index: 9999;
    }

    .hiden {
        display: none !important;
    }

    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .8s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to {
        transform: translateX(10px);
        opacity: 0;
    }
</style>
<template>
    <div>
        <div id="playlist" class="row flex-row-reverse">
            <div class="col-md-6 d-flex flex-wrap pl-lg-0">
                <div class="row w-100 mx-0 align-self-start">
                    <h1><span>Search</span></h1>
                    <div class="d-none d-md-block mx-0 p-0 w-100">Search for music and add it to your playlists</div>
                </div>
                <div class="row align-self-end mx-0 p-0 w-100">
                    <div class="rounded py-1 w-100 title" style="">{{ name }}</div>
                </div>
            </div>
            <div class="col-md-6 pr-md-0">
                <img src="pic/cover4.png" id="coverPicture" class="rounded pr-0 col-lg-12 pr-lg-2 pl-0" alt="cover">
            </div>

        </div>

        <div class="d-inline-flex sticky-top bg-dark rounded w-100" id="player" v-if="playerVisible">
            <div class="btn-group bg-dark rounded h-100" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-dark" v-on:click="prevSong()"><</button>
                <button type="button" class="btn btn-dark" v-on:click="play = !play; update();  play ? audio.play() : audio.pause()"><span v-if="play">||</span><span v-else>&#9658;</span></button>
                <button type="button" class="btn btn-dark" v-on:click="nextSong()">></button>
            </div>
            <div class="h-200 p-1 w-100">
                <input type="range" class="custom-range h-100" v-bind:min="min" v-bind:max="audio.duration" :key="time" v-model="audio.currentTime" id="progress">
            </div>
            <div class="btn-group bg-dark rounded h-100" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-dark" v-on:click="volume = !volume">&#9835;</button>
            </div>
            <div class="h-200 p-1" v-if="volume">
                <input type="range" class="custom-range h-100" min="0.0" max="1.0" step="0.05" v-model="audio.volume" id="progress">
            </div>
            <div class="btn-group bg-dark rounded h-100" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-dark" v-on:click="direct = !direct">
                    <i class="fas fa-sync-alt" v-if="direct"></i>
                    <i class="fas fa-reply" v-else></i>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <div class="input-group mb-3">
                <input type="text" class="form-control" :placeholder="SearchType === 'track' ? 'Author - song name' : 'Author name'" aria-describedby="button-addon2" v-model="search" @keyup.enter="searchSong">
                <div class="input-group-append">
                    <button class="btn btn-dark" type="button" id="button-addon2">
                        <span v-if="SearchType === 'track'" @click="SearchType = 'artist'"><i class="fas fa-music"></i></span>
                        <span v-else @click="SearchType = 'track'"><i class="fas fa-user"></i></span>
                    </button>
                    <button class="btn btn-dark" type="button" id="button-addon3" @click="searchSong">Search</button>
                </div>
            </div>
        </div>
        <div class="text-center" v-if="isSearch">
            <div class="spinner-border align-center" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div id="list" v-else>
            <ul class="list-group">
                <li v-for="(item, index) in list" v-bind:value="item.name" class="clearfix list-group-item list-group-item-action">
                    <span @click="name = item.name; playSong(item.id, index); active = item.id;">
                        <i class="fas fa-pause pr-3 pointed" v-if="active == item.id"></i>
                        <i class="fas fa-play pr-3 pointed" v-else></i>
                    </span>
                    {{ item.name }}
                    <div class="float-right">
                        <i class="fas fa-download float-right pointed" @click="downloadSong(index)"></i>
                        <i class="dropdown dropleft show float-right pointed pr-3">
                            <i role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-plus"></i>
                            </i>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a v-for="(itemPlay, indexPlay) in playlists" @click="addSong(index, itemPlay.id)" class="dropdown-item font-normal">{{ itemPlay.name }}</a>
                            </div>
                        </i>
                    </div>
                </li>
            </ul>
            <div class="text-center pt-2" v-if="list.length && listFull.length !== list.length">
                <button class="btn btn-dark" type="button" @click="increasePage">More</button>
            </div>
        </div>

        <transition name="slide-fade">
            <div class="toastify" v-if="successTify">
                <div class="alert alert-success">
                    <i class="far fa-check-circle fa-4x"> {{ textTify }}</i>
                </div>
            </div>
        </transition>
        <transition name="slide-fade">
            <div class="toastify" v-if="alertTify">
                <div class="alert alert-danger">
                    <i class="fas fa-times fa-4x"> {{ textTify }}</i>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        components: {

        },
        props: [

        ],
        data: function () {
            return {
                playerVisible: false,
                isSearch: false,
                SearchType: 'track',
                search: '',
                play: false,
                volume: false,
                direct: false,
                time: 0.0,
                min: 0.0,
                name: '',
                audio: new Audio(),
                path: '/storage/uploads/',
                type: ['mp3'],

                list: [],
                listFull: [],
                pageSize: 20,
                active: 0,
                editname: '',
                editid: 0,
                authorArray: [],
                author: 0,
                editAuthor: false,
                authorName: '',
                styleArray: [],
                style: 0,
                editStyle: false,
                styleName: '',

                playlists: [],

                successTify: false,
                alertTify: false,
                textTify: '',
            }
        },
        methods:{
            ///// Player
            searchSong() {
                this.list = [];
                this.active = null;
                this.isSearch = true;
                this.pageSize = 20;

                axios
                    .post('api/services/search',{name: this.search, type: this.SearchType})
                    .then(response => {
                        if(response.data.length !== 0) {
                            this.playerVisible = true;
                            this.listFull = response.data;
                            this.list = response.data.slice(0, this.pageSize);
                            this.getLinksSongs();
                        } else {
                            this.alertNotify(false, 'Not Found!');
                        }
                        this.isSearch = false;
                    })
                    .catch(error => {
                        this.isSearch = false;
                        this.alertNotify(false, 'Not Found!');
                    });
            },
            async getLinksSongs() {
                for(var i = 0; i < this.list.length; i++) {
                    if (this.list[i].link == null) {
                        this.list[i].link = await this.getLinkById(this.list[i].id);
                    }
                }
            },
            async getLinkById(id) {
                var linkos = '';
                await axios
                    .post('api/services/id',{id: id})
                    .then(response => {
                        if(response.data !== false) {
                            linkos = response.data;
                        } else {
                            this.alertNotify(false);
                        }
                        this.isSearch = false;
                    })
                    .catch(error => {
                        this.isSearch = false;
                        this.alertNotify(false);
                    });
                return linkos;
            },
            increasePage(){
                this.pageSize+=10;
                this.list = this.list.concat(this.listFull.slice(this.list.length, this.pageSize-1));
            },
            update(){
                //this.$forceUpdate();
                this.time = this.audio.currentTime;
                if (this.audio.ended) {
                    this.nextSong();
                }
                setTimeout(this.update, 1000);
            },
            checkFormat(){
                for(var i = 0; i < this.type.length; i++) {
                    if(!!this.audio.canPlayType && this.audio.canPlayType('audio/'+this.type[i]) !== "") {
                        this.type = this.type[i];
                        break;
                    }
                }
                if(this.type.constructor === Array){
                    alert('Sorry! Your browser not support '+this.type.join(','));
                }
            },
            async playSong(id, index){
                if(id != this.active){
                    if (this.list[index].link === null) {
                        this.audio.src = await this.getLinkById(id);
                        this.list[index].link = this.audio.src;
                    } else {
                        this.audio.src = this.list[index].link;
                    }
                    this.play = true;
                    await this.audio.play();
                    if ('mediaSession' in navigator) {
                        navigator.mediaSession.metadata = new MediaMetadata({
                            title: this.name,
                            artist: this.authorName
                        });
                    }
                    navigator.mediaSession.setActionHandler('previoustrack', this.prevSong);
                    navigator.mediaSession.setActionHandler('nexttrack', this.nextSong);
                    this.findCover(this.name);
                    this.songListened(id);
                } else {
                    this.play = !this.play;
                    if (this.play) {
                        await this.audio.play();
                    } else {
                        this.audio.pause();
                    }
                }
                this.update();
            },
            nextSong(){
                var active;
                if (this.direct === true) {
                    for (var i = 0; i < this.list.length; i++) {
                        if (this.list[i].id === this.active) {
                            active = i;
                            break;
                        }
                    }
                } else {
                    for (var i = 0; i < this.list.length; i++) {
                        if (this.list[i].id === this.active) {
                            active = i + 1;
                            break;
                        }
                    }
                    if (active === this.list.length) {
                        active = 0;
                    }
                }

                this.name = this.list[active].name;
                this.playSong(this.list[active].id, active);
                this.active = this.list[active].id;
            },
            prevSong(){
                var active;
                for (var i = 0; i < this.list.length; i++) {
                    if (this.list[i].id === this.active) {
                        active = i - 1;
                        break;
                    }
                }
                if (active < 0) {
                    active = this.list.length - 1;
                }
                this.name = this.list[active].name;
                this.playSong(this.list[active].id, active);
                this.active = this.list[active].id;
            },


            ////// List
            async downloadSong(index){
                var link = this.list[index].link === null ? await this.getLinkById(this.list[index].id) : this.list[index].link;
                fetch(link)
                    .then(resp => resp.blob())
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        // the filename you want
                        a.download = this.list[index].name + '.' + this.type;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        this.alertNotify(true);
                    })
                    .catch(() => this.alertNotify(false));
            },

            async addSong(index,playlistId){
                if (this.list[index].link === null) {
                    this.alertNotify(false);
                    return;
                }
                await this.addAuthor(this.list[index].author);
                let formData = new FormData();
                formData.append('url', this.list[index].link);
                formData.append('name', this.list[index].name);
                formData.append('playlist_id', playlistId);
                formData.append('author_id', this.author);
                //formData.append('author_id', this.list[index].author !== 0 ? await this.addAuthor(this.list[index].name) : this.list[index].author);
                formData.append('genre_id', '1');
                //formData.append('genre_id', 1 !== 0 ? await this.autoStyle(this.list[index].name) : this.list[index].author);
                formData.append('type', 'url');
                axios.post( 'api/songs',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(response => {
                    if (response.data !== false) {
                        this.alertNotify(true);
                    } else {
                        this.alertNotify(false);
                    }
                })
                .catch(error => {
                    console.log(error);
                    this.alertNotify(false);
                });
            },

            ///////// Playlist
            findCover(name){
                axios
                    .post('api/services/cover', {name: name})
                    .then(response => {
                        if (response.data !== false) {
                            document.getElementById("coverPicture").src = response.data;
                            if ('mediaSession' in navigator) {
                                navigator.mediaSession.metadata = new MediaMetadata({
                                    title: this.name,
                                    artist: this.authorName,
                                    artwork: [
                                        { src: response.data, sizes: '512x512', type: 'image/jpg' },
                                    ]
                                });
                            }
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            getPlayList(){
                axios
                    .get('api/playlists')
                    .then(response => {
                        this.playlists = response.data;
                    })
                    .catch(error => console.log(error));
            },
            async addAuthor(name){
                await axios
                    .post('api/authors', {name: name})
                    .then(response => {
                        this.author = response.data['id'];
                        //return response.data['id'];
                    })
                    .catch(error => console.log(error));
            },
            autoStyle(name){
                axios
                    .post('api/services/genre', {name: name})
                    .then(response => {
                        if (response.data !== false) {
                            return response.data;
                        } else {
                            return 1;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            //Watcher
            songListened(id){
                for(var i = 0; i < this.list.length; i++) {
                    if(this.list[i].id === id) {
                        axios
                            .post('api/histories', {name: this.list[i].name, author: this.list[i].author, inPlaylist: false})
                            .then()
                            .catch(error => console.log(error));
                        break;
                    }
                }
            },

            //UI
            alertNotify(type = true, text = '', delay = 1500){
                if (type) {
                    this.textTify = text;
                    this.successTify = true;
                    setTimeout(() => {this.successTify = false; this.textTify = '';},delay);
                } else {
                    this.textTify = text;
                    this.alertTify = true;
                    setTimeout(() => {this.alertTify = false; this.textTify = '';},delay);
                }
            },
        },
        mounted(){
            this.getPlayList();

            /*this.getAuthor();
            this.getStyle();*/

            this.checkFormat();
            this.audio.src = this.path + 0 + '.' + this.type;
        },
    }
</script>
