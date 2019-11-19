<style>
    /*::-webkit-file-upload-button {
        background: #343a40;
        color: white;
        border-radius: 5px;
        border: none;
    }*/
    .mt-15 {
        margin-top: 10rem !important;
    }

    input[type="file"]{
        display: none;
        position: absolute;
        /*top: -500px;*/
    }

    /*div.file-listing{
        width: 200px;
    }*/

    span.remove-file{
        color: red;
        cursor: pointer;
        float: right;
    }

    /*Spinner*/
    #circle {
        /*position: absolute;*/
        position: relative;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        width: 150px;
        height: 150px;
    }

    .loader {
        width: calc(100% - 0px);
        height: calc(100% - 0px);
        border: 8px solid #162534;
        border-top: 8px solid #F8FAFC;
        border-radius: 50%;
        animation: rotate 5s linear infinite;
    }

    @keyframes rotate {
        100% {transform: rotate(360deg);}
    }
</style>
<template>
    <div>
        <h1>Upload</h1>
        <hr>
        <div v-if="!uploadAnim">
            <p class="pt-2 mb-0">Files</p>
            <input type="file" id="files" ref="files" multiple v-on:change="handleFilesUpload()"/>

            <div class="large-12 medium-12 small-12 cell">
                <ul class="list-group">
                    <li v-for="(file, key) in files" class="clearfix list-group-item list-group-item-action file-listing">{{ file.name }} <span class="remove-file" v-on:click="removeFile( key )">Remove</span></li>
                </ul>
            </div>
            <br>
            <div class="large-12 medium-12 small-12 cell">
                <button class="btn btn-dark" v-on:click="addFiles()">Add Files</button>
            </div>
            <br>

            <p class="pt-2 mb-0">Playlist</p>
            <select class="custom-select" v-model="playlist" name="playlist">
                <option v-for="item in playlistArray" v-bind:value="item.id">
                    {{ item.name }}
                </option>
            </select>
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

            <p class="pt-2 mb-0">Style <a class="badge badge-pill badge-dark text-white" @click="editStyle = true">+</a></p>
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
            <br>
            <br>
            <button class="btn btn-dark col-12" @click="uploadFiles()">Upload</button>
        </div>
        <div v-else>
            <div v-if="spinner">
                <div class="mt-15">
                    <div id="circle">
                        <div class="loader">
                            <div class="loader">
                                <div class="loader">
                                    <div class="loader">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <p>Loading...</p>
                </div>
            </div>
            <div v-else>
                <div v-if="completeUpload" class="text-center">
                    <i class="mt-5 mb-5 far fa-check-circle fa-10x"></i>
                    <p>Complete!!!</p>
                </div>
                <div v-else class="text-center">
                    <i class="mt-5 mb-5 far fa-times-circle fa-10x"></i>
                    <p>Error!!!</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [

        ],
        data: function () {
            return {
                playlistArray: [],
                playlist: 0,
                authorArray: [],
                author: 0,
                editAuthor: false,
                authorName: '',
                styleArray: [],
                style: 0,
                editStyle: false,
                styleName: '',
                files: [],
                uploadAnim: false,
                spinner: false,
                completeUpload: false,
            }
        },
        methods:{
            uploadFiles(){
                this.uploadAnim = true;
                this.spinner = true;
                let formData = new FormData();

                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];

                    formData.append('music[' + i + ']', file);
                }
                formData.append('playlist_id', this.playlist);
                formData.append('author_id', this.author);
                formData.append('genre_id', this.style);
                formData.append('type', 'upload');
                axios.post( 'api/songs',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(response => {
                    //console.log(response);
                    this.spinner = false;
                    this.completeUpload = true;
                    this.files = '';
                    setTimeout(window.location.href = '/list', 1000);
                })
                .catch(error => {
                    console.log(error);
                    this.spinner = false;
                    this.completeUpload = false;
                    setTimeout(window.location.href = '/upload', 1000);
                });
            },
            handleFilesUpload(){
                let uploadedFiles = this.$refs.files.files;

                for( var i = 0; i < uploadedFiles.length; i++ ){
                    this.files.push( uploadedFiles[i] );
                }
            },
            addFiles(){
                this.$refs.files.click();
            },
            removeFile( key ){
                this.files.splice( key, 1 );
            },

            getPlayList(){
                axios
                    .get('api/playlists')
                    .then(response => {
                        this.playlistArray = response.data;
                        this.playlist = response.data[0]['id'];
                    })
                    .catch(error => console.log(error));
            },
            getAuthor(){
                axios
                    .get('api/authors')
                    .then(response => {
                        this.authorArray = response.data;
                        this.author = response.data[0]['id'];
                    })
                    .catch(error => console.log(error));
            },
            getStyle(){
                axios
                    .get('api/genres')
                    .then(response => {
                        this.styleArray = response.data;
                        this.style = response.data[0]['id'];
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
        },
        mounted(){
            this.getPlayList();
            this.getAuthor();
            this.getStyle();
        },
    }
</script>
