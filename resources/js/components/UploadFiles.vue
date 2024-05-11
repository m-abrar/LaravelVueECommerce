<template>
    <section class="h-100 h-custom bg-light min-h-content" >
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col d-flex justify-content-center align-items-center">
                        
                    <div class="card border-0 " style="width:100%">
                        <div class="card-body">
                            <h3 class="mb-4">Upload file</h3>
                            <hr/>
                            <Dropzone paramName="image"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </template>
    <script>
    import Dropzone from '../components/Dropzone.vue'
    
    export default {
        setup() {
            
        },
        components:{Dropzone}
    }
    </script>

<style scoped>
.min-h-content{
    min-height: calc(100vh - 82px - 148px);
}

</style>
