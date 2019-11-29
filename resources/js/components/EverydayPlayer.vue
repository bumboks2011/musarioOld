<style>
    .pointed {
        cursor: pointer;
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
        <div id="playlist">
            <img src="pic/cover.png" id="coverPicture" class="rounded float-left col-md-12 pr-0 col-lg-5 pr-lg-2 pl-0" alt="cover">
            <h1 v-if="!editPlaylistName"> <span @click="editPlaylistName = true">{{ playlistName }}</span></h1>
            <div v-else class="input-group mb-3 col-7 col-sm-7">
                <input type="text" class="form-control" placeholder="Enter new playlist name" v-model="playlistName">
                <div class="input-group-append">
                    <button type="button" class="btn btn-dark" @click="editPlayList(id,playlistName); editPlaylistName = false">edit</button>
                    <button type="button" class="btn btn-dark" @click="removePlayList(id,playlistName); editPlaylistName = false">delete</button>
                </div>
            </div>

            <div class="float-left p-1" v-for="item in playlists" style="font-size: 18px;">
                <a class="badge badge-pill badge-dark text-white p-2 pointed" @click="id = item.id; playlistName = item.name;"  v-model="item.id">{{ item.name }}</a>
            </div>

            <div class="float-left p-1" style="font-size: 18px;">
                <a class="badge badge-pill badge-dark text-white p-2 pointed" data-toggle="modal" data-target="#addModal">+</a>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title text-white" id="addModalLabel">Add playlist</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body bg-white">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter new playlist name" v-model="addPlaylistName">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-dark" data-dismiss="modal" @click="addPlayList(addPlaylistName)">add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <br>

        <div class="d-inline-flex bg-dark rounded w-100" id="player" v-if="playerVisible">
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

        <!--<div class="pt-2">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Author - song name" aria-describedby="button-addon2" v-model="search">
                <div class="input-group-append">
                    <button class="btn btn-dark" type="button" id="button-addon2" @click="searchSong">Search</button>
                </div>
            </div>
        </div>-->
        <div class="text-center" v-if="isSearch">
            <div class="spinner-border align-center" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div id="list" v-else>
            <ul class="list-group">
                <li v-for="(item, index) in list" v-bind:value="item.name" class="clearfix list-group-item list-group-item-action">
                    <span @click="name = item.name; playSong(item.ya_id, index); active = item.ya_id;">
                        <i class="fas fa-pause pr-3 pointed" v-if="active == item.ya_id"></i>
                        <i class="fas fa-play pr-3 pointed" v-else></i>
                    </span>
                    {{ item.name }}
                    <!--<a href="#" class="badge badge-pill badge-dark">{{ item.author }}</a>-->

                    <i class="fas fa-plus float-right pointed" @click="addSong(index)"></i>
                    <i class="fas fa-download float-right pr-3 pointed" @click="downloadSong(index)"></i>
                </li>
            </ul>
        </div>

        <transition name="slide-fade">
            <div class="toastify" v-if="successTify">
                <div class="alert alert-success">
                    <i class="far fa-check-circle fa-4x"></i>
                </div>
            </div>
        </transition>
        <transition name="slide-fade">
            <div class="toastify" v-if="alertTify">
                <div class="alert alert-danger">
                    <i class="fas fa-times fa-4x"></i>
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
                activePlaylist: 0,
                id: 0,
                playlistName: '',
                editPlaylistName: false,
                addPlaylistName: '',

                successTify: false,
                alertTify: false,
            }
        },
        methods:{
            ///// Player
            getEverydayPlayList() {
                this.list = [];
                this.active = null;
                this.isSearch = true;

                axios
                    .get('api/services/everyday')
                    .then(response => {
                        if(response.data !== false) {
                            this.playerVisible = true;
                            this.list = response.data;
                            this.getLinksSongs();
                            /*this.name = this.list[0].name;
                            this.playSong(this.list[0].id,0);
                            this.active = this.list[0].id;*/
                            console.log(response.data);
                        } else {
                            this.alertNotify(false);
                        }
                        this.isSearch = false;
                    })
                    .catch(error => {
                        this.isSearch = false;
                        this.alertNotify(false);
                    });
            },
            async getLinksSongs() {
                for(var i = 0; i < this.list.length; i++) {
                    this.list[i].link = await this.getLinkById(this.list[i].ya_id);
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
            update(){
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
                    this.findCover(this.name);
                    this.songListened(index);
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
                        if (this.list[i].ya_id === this.active) {
                            active = i;
                            break;
                        }
                    }
                } else {
                    for (var i = 0; i < this.list.length; i++) {
                        if (this.list[i].ya_id === this.active) {
                            active = i + 1;
                            break;
                        }
                    }
                    if (active === this.list.length) {
                        active = 0;
                    }
                }

                this.name = this.list[active].name;
                this.playSong(this.list[active].ya_id, active);
                this.active = this.list[active].ya_id;
            },
            prevSong(){
                var active;
                for (var i = 0; i < this.list.length; i++) {
                    if (this.list[i].ya_id === this.active) {
                        active = i - 1;
                        break;
                    }
                }
                if (active < 0) {
                    active = this.list.length - 1;
                }
                this.name = this.list[active].name;
                this.playSong(this.list[active].ya_id, active);
                this.active = this.list[active].ya_id;
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

            async addSong(index){
                if (this.list[index].link === null) {
                    this.alertNotify(false);
                    return;
                }
                await this.addAuthor(this.list[index].author);
                let formData = new FormData();
                formData.append('url', this.list[index].link);
                formData.append('name', this.list[index].name);
                formData.append('playlist_id', this.id);
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
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            getPlayList(stretch = false){
                axios
                    .get('api/playlists')
                    .then(response => {
                        console.log(response.data);
                        this.playlists = response.data;
                        if (stretch === false) {
                            this.playlistName = response.data[0]['name'];
                            this.id = response.data[0]['id'];
                        }
                    })
                    .catch(error => console.log(error));
            },
            editPlayList(id, name){
                axios
                    .put('api/playlists/' + id, {name: name})
                    .then(response => {
                        this.playlistName = name;
                        this.id = id;
                        this.getPlayList(true);
                    })
                    .catch(error => console.log(error));
            },
            removePlayList(id, name){
                if (confirm("Are you sure?")) {
                    axios
                        .delete('api/playlists/' + id)
                        .then(response => {
                            //console.log(json);
                            if(response.data === false) {
                                alert('Delete playlist error!');
                            }
                            this.playlists = response.data;
                            this.playlistName = response.data[0]['name'];
                            this.id = response.data[0]['id'];
                        })
                        .catch(error => console.log(error));
                }
            },
            addPlayList(name){
                axios
                    .post('api/playlists', {name: name})
                    .then(response => {
                        this.playlistName = response.data['name'];
                        this.id = response.data['id'];
                        this.getPlayList();
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
            songListened(index){
                axios
                    .post('api/histories', {name: this.list[index].name, author: this.list[index].author, inPlaylist: false})
                    .then()
                    .catch(error => console.log(error));
            },

            //UI
            alertNotify(type = true, delay = 1500){
                if (type) {
                    this.successTify = true;
                    setTimeout(() => {this.successTify = false},delay);
                } else {
                    this.alertTify = false;
                    setTimeout(() => {this.alertTify = false},delay);
                }
            },
        },
        mounted(){
            this.getPlayList();

            /*this.getAuthor();
            this.getStyle();*/

            this.checkFormat();
            this.audio.src = this.path + 0 + '.' + this.type;
            this.getEverydayPlayList();
        },
    }
</script>
