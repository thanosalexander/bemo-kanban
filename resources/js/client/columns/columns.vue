<template>
    <div class="columns p-3 rounded-sm">

        <column v-for="(column,index) in orderedColumns" :column="column"
                :key="column.id"
                @deleted="destroy(index)"
        ></column>

        <new-column @added="add"></new-column>


        <button id="export-button" class="btn btn-success btl-xl" @click="download">
            Export DB
        </button>
    </div>
</template>

<script>

export default {
    name: "columns",
    components: {
        'new-column' : ()=>import('./NewColumn'),
        'column' : ()=>import('./column'),
    },

    mounted() {
        this.fetchColumns()
    },

    data(){
        return {
            columns: []
        }
    },

    computed: {
        orderedColumns: function () {
            return _.orderBy(this.columns, 'order')
        }
    },

    methods:{

        download(){
            axios.post('/db-download')
                .then((response)=>{
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'dump.sql');
                    document.body.appendChild(link);
                    link.click();
                })
        },

        fetchColumns(){
            axios.get('/columns')
                .then((response)=>{
                    this.columns = response.data.data
                })
        },

        add(column){
            this.columns.push(column)
        },

        destroy(index){
            this.columns.splice(index,1)
        }
    }
}
</script>

<style scoped>

</style>
