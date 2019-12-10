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
                    <h1><span>Library</span></h1>
                    <div class="d-none d-md-block mx-0 p-0 w-100">All your music is here</div>
                </div>
                <div class="row align-self-end mx-0 p-0 w-100">
                    <div class="rounded py-1 w-100 title" style="">{{ name }}</div>
                </div>
            </div>
            <div class="col-md-6 pr-md-0">
                <img src="pic/cover2.png" id="coverPicture" class="rounded pr-0 col-lg-12 pr-lg-2 pl-0" alt="cover">
            </div>

        </div>

        <div class="d-inline-flex bg-dark rounded w-100" id="player">
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
        <div id="list">
            <ul class="list-group">
                <li v-for="(item, index) in list" v-bind:value="item.name" class="clearfix list-group-item list-group-item-action">
                    <span @click="name = item.name; playSong(item.id); active = item.id;">
                        <i class="fas fa-pause pr-3" v-if="active == item.id"></i>
                        <i class="fas fa-play pr-3" v-else></i>
                    </span>
                    {{ item.name }}
                    <div class="float-right">
                        <i class="fas fa-pen float-right pointed" @click="openModal(item)" data-toggle="modal" data-target="#editModal"></i>
                        <i class="dropdown dropleft show float-right pointed pr-3">
                            <i role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-plus"></i>
                            </i>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a v-for="(itemPlay, indexPlay) in playlists" @click="addSong(item.id, itemPlay.id)" class="dropdown-item font-normal">{{ itemPlay.name }}</a>
                            </div>
                        </i>
                    </div>
                </li>
            </ul>
            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title text-white" id="editModalLabel">Edit song</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body bg-white">
                            <div class="input-group mb-0">
                                <input type="text" class="form-control" placeholder="Enter new song name" v-model="editname">
                            </div>

                            <p class="pt-2 mb-0">Author <a class="badge badge-pill badge-dark text-white" @click="editAuthor = true">+</a></p>
                            <div v-if="editAuthor" class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter new author name" v-model="authorName">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-dark" @click="editAuthor = false; addAuthor();">add</button>
                                </div>
                            </div>
                            <select class="custom-select" v-model="author" name="author">
                                <option v-for="item in authorArray" v-bind:value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>

                            <p class="pt-2 mb-0">Style
                                <a class="badge badge-pill badge-dark text-white" @click="editStyle = true">+</a>
                                <a class="badge badge-pill badge-dark text-white" @click="autoStyle()">find</a>
                            </p>
                            <div v-if="editStyle" class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter new style name" v-model="styleName">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-dark" @click="editStyle = false; addStyle();">add</button>
                                </div>
                            </div>
                            <select class="custom-select" v-model="style" name="style">
                                <option v-for="item in styleArray" v-bind:value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                        <div class="modal-footer bg-white">
                            <button type="button" class="btn btn-dark col-sm-12" data-dismiss="modal" @click="editSong()">save</button>
                        </div>
                    </div>
                </div>
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

                successTify: false,
                alertTify: false,
            }
        },
        methods:{
            ///// Player
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
            playSong(id){
                if(id != this.active){
                    this.audio.src = this.path + id + '.' + this.type;
                    this.play = true;
                    this.audio.play();
                    this.findCover(this.name);
                } else {
                    this.play = !this.play;
                    if (this.play) {
                        this.audio.play();
                    } else {
                        this.audio.pause();
                    }
                }
                this.update();
            },
            nextSong(){
                var active;
                if (this.direct == true) {
                    for (var i = 0; i < this.list.length; i++) {
                        if (this.list[i].id == this.active) {
                            active = i;
                            break;
                        }
                    }
                } else {
                    for (var i = 0; i < this.list.length; i++) {
                        if (this.list[i].id == this.active) {
                            active = i + 1;
                            break;
                        }
                    }
                    if (active == this.list.length) {
                        active = 0;
                    }
                }

                this.name = this.list[active].name;
                this.playSong(this.list[active].id);
                this.active = this.list[active].id;
            },
            prevSong(){
                var active;
                for (var i = 0; i < this.list.length; i++) {
                    if (this.list[i].id == this.active) {
                        active = i - 1;
                        break;
                    }
                }
                if (active < 0) {
                    active = this.list.length - 1;
                }
                this.name = this.list[active].name;
                this.playSong(this.list[active].id);
                this.active = this.list[active].id;
            },


            ////// List
            getSongList(){
                axios
                    .get('api/songs')
                    .then(response => {
                        this.list = response.data;
                    })
                    .catch(error => console.log(error));
            },
            addSong(id,playlistId){
                axios
                    .post('api/orders/' + playlistId,{song: id})
                    .then(response => {
                        if(response.data === true) {
                            this.alertNotify();
                            this.getSongList();
                        } else {
                            this.alertNotify(false);
                        }
                    })
                    .catch(error => console.log(error));
            },
            getAuthor(){
                axios
                    .get('api/authors')
                    .then(response => {
                        this.authorArray = response.data;
                        this.author = response.data['id'];
                    })
                    .catch(error => console.log(error));
            },
            getStyle(strip = false){
                axios
                    .get('api/genres')
                    .then(response => {
                        this.styleArray = response.data;
                        if(strip === false) {
                            this.style = response.data['id'];
                        }
                    })
                    .catch(error => console.log(error));
            },
            addAuthor(){
                axios
                    .post('api/authors', {name: this.authorName})
                    .then(response => {
                        this.getAuthor();
                        this.author = response.data['id'];
                    })
                    .catch(error => console.log(error));
            },
            addStyle(){
                axios
                    .post('api/genres', {name: this.styleName})
                    .then(response => {
                        this.getStyle();
                        this.style = response.data['id'];
                    })
                    .catch(error => console.log(error));
            },
            openModal(edit){
                this.editname = edit.name;
                this.editid = edit.id;
                this.author = edit.author_id;
                this.style = edit.genre_id;
            },
            editSong(){
                axios
                    .put('api/songs/' + this.editid, {name: this.editname, author_id: this.author, genre_id: this.style})
                    .then(response => {
                        if(response.data === true) {
                            this.getSongList();
                        } else {
                            alert('Edit song error!');
                        }
                    })
                    .catch(error => console.log(error));
            },
            autoStyle(){
                axios
                    .post('api/services/genre', {name: this.editname})
                    .then(response => {
                        if (response.data !== false) {
                            this.styleArray = this.getStyle(true);
                            this.style = response.data;
                        } else {
                            alert('Auto style not found!');
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        alert('Auto style not found!');
                    });
            },
            findCover(name){
                axios
                    .post('api/services/cover', {name: this.name})
                    .then(response => {
                        if (response.data !== false) {
                            document.getElementById("coverPicture").src = response.data;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            ///////// Playlist
            getPlayList(stretch = false){
                axios
                    .get('api/playlists')
                    .then(response => {
                        this.playlists = response.data;
                        if (stretch === false) {
                            this.getSongList();
                        }
                    })
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

            this.getAuthor();
            this.getStyle();

            this.checkFormat();
            this.audio.src = this.path + 0 + '.' + this.type;
        },
    }
</script>
