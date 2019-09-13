<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Статистика статей</div>
                <div class="card-body p-0">
                   <div class="col-md-12 p-0">
                       <table class="table text-center">
                           <thead>
                            <tr>
                                <td>Всего</td>
                                <td>Опубликовано</td>
                                <td>Не опубликовано</td>
                                <td>Удалено</td>
                            </tr>
                           </thead>
                           <tbody>
                            <tr>
                                <td>{{ postdata.score }}</td>
                                <td>{{ postdata.published }}</td>
                                <td>{{ postdata.hides }}</td>
                                <td>{{ postdata.deleted }}</td>
                            </tr>
                           </tbody>
                       </table>
                   </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'postdata'
        ]
    }
</script>
