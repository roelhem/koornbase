<template>

    <div>

        <person-banner v-if="person" :person="person"></person-banner>

        <person-header v-if="person" :person="person"></person-header>


        <person-navbar :pages="pages" @change-page="p => { page = p }"></person-navbar>

        <component v-if="person" :is="page.component" :person="person"></component>

    </div>

</template>

<script>
    import axios from 'axios';

    export default {

        props:{
            src:{
                type:String,
                default:function() {
                    return window.location.href;
                }
            }
        },

        data: function() {

            const pags = [
                {
                    name:'main',
                    label:'Tijdlijn',
                    component: require('./pages/main')
                },
                {
                    name:'contact',
                    label:'Contactgegevens',
                    component: require('./pages/contact')
                }
            ];


            return {
                isLoading: false,
                person: null,
                pages: pags,
                page: pags[0]
            }
        },

        methods:{
            load() {
                this.isLoading = true;

                axios.get(this.src).then(response => {

                    this.person = response.data.data;

                    this.loading = false;
                }).catch(e => {
                    console.log(e);

                    this.loading = false;
                });

            }
        },


        created() {
            this.load();
        },


        components: {
            'person-banner': require('./banner'),
            'person-header': require('./header'),
            'person-navbar': require('./navbar')
        }

    }
</script>

<style scoped>

</style>