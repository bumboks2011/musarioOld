<style>

</style>
<template>
    <div>
        <div id="documentation"></div>
    </div>
</template>

<script>
    import SwaggerUI from 'swagger-ui'

    export default {
        components: {
            SwaggerUI,
        },
        props: [

        ],
        data: function () {
            return {

            }
        },
        methods:{

        },
        mounted(){
            SwaggerUI({
                url: "faq/swagger.yaml",
                dom_id: '#documentation',
            });
        },
    }
</script>
