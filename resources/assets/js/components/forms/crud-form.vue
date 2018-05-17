<template>

    <form class="card" :action="action" method="post">

        <div class="card-header">
            <h3 class="card-title">{{ title }}</h3>
        </div>

        <div class="dimmer" :class="{active:isLoading}">
            <div class="loader"></div>

            <div class="dimmer-content">
                <div class="card-body">
                    <slot>Dit formulier heeft geen inhoud...</slot>
                </div>

                <div class="card-footer text-right">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary ml-auto">{{ submitText || 'Opslaan' }}</button>
                    </div>
                </div>

            </div>
        </div>

        <input type="hidden" name="_token" :value="token">

    </form>

</template>

<script>
    export default {
        name: "crud-form",
        props: {
            'action':{
                type:String,
                default: function() {
                    return window.location.href
                }
            },
            'title':String,
            'submitText':String,

            'isLoading':{
                type:Boolean,
                default:false
            }
        },
        computed: {
            token: function() {
                const metas = window.document.getElementsByTagName('meta');

                for(let i = metas.length - 1; i >= 0; i--) {
                    const meta = metas[i];
                    if(meta.name === "csrf-token") {
                        return meta.content;
                    }
                }

                return null;
            }
        }
    }
</script>

<style scoped>

</style>