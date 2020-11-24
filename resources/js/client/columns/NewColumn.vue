<template>
    <div>
        <button class="btn btn-info" @click="formOpen=true" v-if="!formOpen">
            Add new column
        </button>

        <form class="column" @submit.prevent.stop="save" v-if="formOpen">

            <div class="column-header">
                <h3>Add new column</h3>
            </div>

            <div class="column-body">
                <div class="form-group" :class="{'has-error' : hasError('name')}">
                    <label for="name">Name</label>
                    <input type="text" v-model="form.data.name" class="form-control" :class="{'is-invalid' : hasError('name')}">
                    <div class="invalid-feedback" v-if="hasError('name')" v-text="getError('name')"></div>
                </div>

                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-sm btn-warning" @click="cancel">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </div>


        </form>
    </div>
</template>

<script>
export default {
    name: "NewColumn",
    data(){
        return {
            formOpen :false,
            form:{
                data:{
                    name:null
                },
                errors:{}
            }
        }
    },

    methods:{
        cancel(){
            this.form.data.name = null
            this.form.errors = {}
            this.formOpen = false;
        },

        save(){
            axios.post('/columns',this.form.data)
                .then((response)=>{
                    this.cancel()
                    this.$emit('added',response.data.data)
                })
                .catch((error)=>{
                    if(error.response.status===422){
                        this.form.errors = error.response.data.errors
                    }
                })
        },

        hasError(field){
            return this.form.errors.hasOwnProperty(field)
        },

        getError(field){
            if(this.hasError(field)){
                return this.form.errors[field][0]
            }

            return null
        }
    }
}
</script>

<style scoped>

</style>
