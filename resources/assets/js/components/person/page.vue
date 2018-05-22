<template>

    <div>

        <person-banner v-if="person" :person="person"></person-banner>

        <person-header v-if="person" :person="person"></person-header>

        <b-container>
            <b-nav tabs>
                <b-nav-item v-for="(page, pageIndex) in pages"
                            :key="page.name"
                            :active="pageIndex === currentPageIndex"
                            :disabled="page.component === null"
                            @click="changePage(page, pageIndex)">
                    {{ page.label }}
                </b-nav-item>

                <div class="ml-auto px-4 py-2">


                    <b-button class="mx-2" :href="editPageLink" variant="outline-primary"><a class="fa fa-pencil mr-2"></a>Bewerken</b-button>




                    <b-dropdown id="person_options_dropdown" text="Opties" variant="outline-secondary" right style="background-color: transparent;">
                        <b-dropdown-item>Lidmaatschap toevoegen</b-dropdown-item>
                        <b-dropdown-item>Account aanmaken</b-dropdown-item>
                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-item>Verwijderen</b-dropdown-item>
                    </b-dropdown>



                </div>
            </b-nav>
        </b-container>

        <component v-if="person" :is="currentPage.component" :person="person"></component>

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

            return {
                isLoading: false,
                person: null,
                pages: [
                    {
                        name:'main',
                        label:'Tijdlijn',
                        component: require('./pages/main')
                    },
                    {
                        name:'info',
                        label:'Info',
                        component: null
                    },
                    {
                        name:'contact',
                        label:'Contactgegevens',
                        component: require('./pages/contact')
                    },
                    {
                        name:'events',
                        label:'Evenementen',
                        component: null
                    },
                    {
                        name:'groups',
                        label:'Groepen',
                        component: null
                    },
                    {
                        name:'statistics',
                        label:'Statestieken',
                        component: null
                    },
                    {
                        name:'accounts',
                        label:'Accounts',
                        component: null
                    }
                ],
                currentPageIndex: 0
            }
        },


        computed: {
            currentPage:function() {
                return this.pages[this.currentPageIndex];
            },

            editPageLink:function() {
                return '/people/'+this.person.id+'/edit';
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

            },

            changePage(page, pageIndex) {
                if(page.component !== null) {
                    this.currentPageIndex = pageIndex;
                }
            }
        },


        created() {
            this.load();
        },


        components: {
            'person-banner': require('./banner'),
            'person-header': require('./header')
        }

    }
</script>

<style scoped>

</style>