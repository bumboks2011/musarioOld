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
            <img src="pic/cover3.png" id="coverPicture" class="rounded float-left col-md-12 pr-0 col-lg-5 pr-lg-2 pl-0" alt="cover">
            <h1 v-if="!editPlaylistName"> <span @click="editPlaylistName = true">{{ playlistName }}</span></h1>
            <div v-else class="input-group mb-3 col-7 col-sm-7">
                <input type="text" class="form-control" placeholder="Enter new playlist name" v-model="playlistName">
                <div class="input-group-append">
                    <button type="button" class="btn btn-dark" @click="editPlayList(id,playlistName); editPlaylistName = false">edit</button>
                    <button type="button" class="btn btn-dark" @click="removePlayList(id,playlistName); editPlaylistName = false">delete</button>
                </div>
            </div>

            <!--<button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ playlistName }}
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" @click="id = item.id; name = item.name; getSongList();" v-for="item in playlists" v-model="item.id">{{ item.name }}</a>
            </div>-->

            <div class="float-left p-1" v-for="item in playlists" style="font-size: 18px;">
                <a class="badge badge-pill badge-dark text-white p-2 pointed" @click="id = item.id; playlistName = item.name; getSongList();"  v-model="item.id">{{ item.name }}</a>
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
                <draggable v-model="list" group="music" @end="onEnd">
                    <li v-for="(item, index) in list" v-bind:value="item.name" class="clearfix list-group-item list-group-item-action">
                        <span @click="name = item.name; playSong(item.id); active = item.id;">
                            <i class="fas fa-pause pr-3 pointed" v-if="active == item.id"></i>
                            <i class="fas fa-play pr-3 pointed" v-else></i>
                        </span>
                        {{ item.name }}
                        <!--<a href="#" class="badge badge-pill badge-dark">{{ item.style }}</a>-->
                        <!--<a href="#" class="badge badge-pill badge-dark">{{ item.author }}</a>-->
                        <i class="fas fa-trash float-right pointed" @click="deleteSong(item.orderId)"></i>
                        <i class="fas fa-pen float-right pr-3 pointed" @click="openModal(item)" data-toggle="modal" data-target="#editModal"></i>
                        <i class="fas fa-download float-right pr-3 pointed" @click="downloadSong(index)"></i>
                    </li>
                </draggable>
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
    import draggable from 'vuedraggable';
    export default {
        components: {
            draggable,
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
                activePlaylist: 0,
                id: 0,
                playlistName: '',
                editPlaylistName: false,
                addPlaylistName: '',

                /*UI toast*/
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
                    this.songListened(id);
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
            downloadSong(index){
                fetch(this.path + this.list[index].id + '.' + this.type)
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
            onEnd(evt) {
                var itemEl = evt.item;
                if (evt.newIndex !== evt.oldIndex) {
                    this.changePos(evt.newIndex);  // element's new index within new parent
                }
            },
            getSongList(id = this.id){
                axios
                    .get('api/orders/' + id)
                    .then(response => {
                        this.list = this.sortList(0, response.data, [], this);
                        //console.log(this.list);
                    })
                    .catch(error => console.log(error));
            },
            sortList(next, mas, sorted, then) {
                mas.forEach(function(item, i, arr) {
                    if (item.pos_id === next) {
                        sorted[sorted.length] = item;
                        then.sortList(item.orderId, arr, sorted, then);
                    }
                });
                return sorted;
            },
            changePos(change){
                var id = this.list[change].orderId,
                    next = change - 1;
                if (next < 0) {
                    next = 0;
                } else {
                    next = this.list[next].orderId;
                }
                axios
                    .put('api/orders/' + id, {next: next})
                    .then(response => {
                        if(response.data === true) {
                            //this.getSongList();
                        } else {
                            this.getSongList();
                            alert('Change position error!');
                        }
                    })
                    .catch(error => console.log(error));
            },
            deleteSong(id){
                if (confirm("Are you sure?")) {
                    axios
                        .delete('api/orders/' + id)
                        .then(response => {
                            if(response.data === true) {
                                this.getSongList();
                            } else {
                                alert('Delete song error!');
                            }
                        })
                        .catch(error => console.log(error));
                }
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

            ///////// Playlist
            getPlayList(stretch = false){
                axios
                    .get('api/playlists')
                    .then(response => {
                        console.log(response.data);
                        this.playlists = response.data;
                        if (stretch === false) {
                            this.playlistName = response.data[0]['name'];
                            this.id = response.data[0]['id'];
                            this.getSongList(response.data[0]['id']);
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
                        //list.getSongList(json[0]['id']);
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
                            this.getPlayList();
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
                        this.getSongList(response.data['id']);
                    })
                    .catch(error => console.log(error));
            },
            nextPlaylist(){
                this.activePlaylist += 1;
                if (this.activePlaylist == this.playlists.length) {
                    this.activePlaylist = 0;
                }
                this.id = this.playlists[this.activePlaylist]['id'];
                this.getSongList();
            },
            prevPlaylist(){
                this.activePlaylist--;
                if (this.activePlaylist < 0) {
                    this.activePlaylist = this.playlists.length - 1;
                }
                this.id = this.playlists[this.activePlaylist]['id'];
                this.getSongList();
            },

            //Watcher
            songListened(id){
                for(var i = 0; i < this.list.length; i++) {
                    if(this.list[i].id === id) {
                        axios
                            .post('api/histories', {name: this.list[i].name, author: this.list[i].author, inPlaylist: true})
                            .then()
                            .catch(error => console.log(error));
                        break;
                    }
                }
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
