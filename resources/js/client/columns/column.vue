<template>
    <div class="column" >
        <div class="column-header d-flex justify-content-between align-items-center">
            <h5 class="font-weight-bold">{{ column.name }}</h5>
            <button type="button" class="close" @click.prevent="destroy">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="column-body">

            <draggable v-model="cards" group="cards"  :data-column-id="column.id"  @add="updateSort" @remove="updateSort" @sort="updateSort">
                <div class="card-mini" v-for="(card,index) in cards " @click.self="edit(card)" :data-card-id="card.id">
                    <div class="card-mini__header" @click.self="edit(card,index)">
                        <h5 class="font-weight-bold" >{{ card.title }}</h5>
                        <button type="button" class="close" @click.prevent="destroyCard(card,index)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </draggable>


            <button class="btn btn-sm btn-info w-100" @click="add">
                Add card
            </button>
        </div>


        <modal :name="`card-form-${column.id}`" class="card-form" height="auto">
            <form @submit.prevent.stop="save" >
                <div class="card-form__header">
                    <h5 class="font-weight-bold">{{ form.title }}</h5>
                </div>
                <div class="card-form__body">

                    <div class="form-group" :class="{'has-error' : hasError('title')}">
                        <label for="title">Title</label>
                        <input type="text" v-model="form.data.title" class="form-control" :class="{'is-invalid' : hasError('title')}">
                        <div class="invalid-feedback" v-if="hasError('title')" v-text="getError('title')"></div>
                    </div>

                    <div class="form-group" :class="{'has-error' : hasError('description')}">
                        <label for="description">Description</label>
                        <textarea  v-model="form.data.description" class="form-control" :class="{'is-invalid' : hasError('description')}"></textarea>
                        <div class="invalid-feedback" v-if="hasError('description')" v-text="getError('description')"></div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-right">
                            <button class="btn btn-sm btn-warning" @click.prevent="cancel">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                Save
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </modal>

    </div>
</template>

<script>

import draggable from 'vuedraggable'
export default {
    name: "column",

    components:{
        draggable,
    },

    props: {
        column: {
            type: Object,
            required: true
        }
    },

    mounted() {
        this.fetchCards()
    },

    data(){
        return {
            cards:[],
            form:{
                id:null,
                title:null,
                data:{
                    title:null,
                    description:null,
                },
                errors:{}
            }
        }
    },

    methods:{

        fetchCards(){
            axios.get(`${this.column.id}/cards`)
                .then((response)=>{
                    this.cards = _.orderBy(response.data.data, 'order')
                })
        },

        destroy(){
            axios.delete(`/columns/${this.column.id}`)
                .then((response)=>{
                    this.$emit('deleted')
                })
        },

        destroyCard(card,index){
            axios.delete(`/cards/${card.id}`)
                .then((response)=>{
                    this.cards.splice(index,1)
                })
        },

        add(){
            this.form.title = "Add new card"
            this.$modal.show(`card-form-${this.column.id}`)
        },

        edit(card,index){
            this.form.title = "Edit card"
            this.form.id = card.id
            this.form.index = index
            this.form.data.title = card.title
            this.form.data.description = card.description
            this.$modal.show(`card-form-${this.column.id}`)
        },




        updateSort(evt) {

            let columnId = this.column.id;

            var cards = this.cards.map((card, index)=> {
                return {
                    cardId: card.id,
                    order: index ,
                    columnId: columnId
                }
            })

            axios({
                method: 'post',
                url: `/columns/${this.column.id}/sort`,
                data: {
                    'cards':cards
                }
            })
        },


        save(){

            let method = this.form.id === null ? 'post' : 'put'
            let url = this.form.id === null ? `${this.column.id}/cards` : `/cards/${this.form.id}`

            axios({
                method: method,
                url: url,
                data: this.form.data
            })
                .then((response)=>{
                    if(method==='put'){
                        this.cards.splice(this.form.index,1,response.data.data)
                    }
                    else{
                        this.cards.push(response.data.data)
                    }

                    this.cancel()
                })
                .catch((error)=>{
                    if(error.response.status===422){
                        this.form.errors = error.response.data.errors
                    }
                })
        },

        cancel(){
            this.$modal.hide(`card-form-${this.column.id}`)
            this.form.title = null
            this.form.id = null
            this.form.index = null
            this.form.data.title = null
            this.form.data.description = null
            this.form.errors = {}
        },

        hasError(field){
            return this.form.errors.hasOwnProperty(field)
        },

        getError(field){
            if(this.hasError(field)){
                return this.form.errors[field][0]
            }

            return null
        },

    }
}
</script>

<style scoped>

</style>
