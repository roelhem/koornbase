<template>

    <b-container>

        <b-row>

            <b-col lg="3">

                <search-column-select-card :collapsed.sync="cardCollapsed" v-model="columns" />

            </b-col>

            <b-col>
                    <tabler-input-icon append="search">
                        <b-form-input placeholder="Zoeken..."></b-form-input>
                    </tabler-input-icon>

                    <search-per-page-input></search-per-page-input>
                
                    <search-simple-pager v-model="page" :last-page="10" />


                    <search-sort-input v-model="sort" :sort-order.sync="sortOrder">
                        <option>a</option><option>b</option><option>c</option>
                    </search-sort-input>

                    <pre>
                        {{ sort }}
                        {{ sortOrder }}
                    </pre>

            </b-col>

        </b-row>

        <b-row>

            <b-col v-for="styleCard in styleCards" :key="'style-card-'+styleCard.style.name" lg="4">

                <show-style-card :subject-style="styleCard.style" :collapsed.sync="styleCard.collapsed"></show-style-card>

            </b-col>

        </b-row>

    </b-container>

</template>

<script>

    import ALL_STYLES from "../constants/styles";
    import RefTagGroup from "./RefTagGroup";
    import TablerCard from "./TablerCard";
    import ShowStyleCard from "./ShowStyleCard";
    import FormSwitch from "./FormSwitch";
    import SearchColumnSelectCard from "./SearchColumnSelectCard";
    import TablerInputIcon from "./TablerInputIcon";
    import SearchPerPageInput from "./SearchPerPageInput";
    import SearchSimplePager from "./SearchSimplePager";
    import SearchSortInput from "./SearchSortInput";

    export default {
        components: {
            SearchSortInput,
            SearchSimplePager,
            SearchPerPageInput,
            TablerInputIcon,
            SearchColumnSelectCard,
            FormSwitch,
            ShowStyleCard,
            TablerCard,
            RefTagGroup,
        },
        name: "the-page-home",

        data:function() {
            return {
                styleCards: ALL_STYLES.map(el => {
                    return {
                        style: el,
                        collapsed: false
                    };
                }),
                cardCollapsed:false,
                page:2,
                sort:undefined,
                sortOrder:'desc',
                columns:[
                    {
                        key:'avatar',
                        label: '',
                        name:'Avatar',
                        visible:true,
                    },
                    {
                        key:'id',
                        label:'ID',
                        name:'ID',
                        sortable:'id',
                        visible:false
                    },
                    {
                        key:'name',
                        label:'Hele Naam',
                        name:'Volledige Naam',
                        sortable:'name_first',
                        visible:true
                    },
                    {
                        key:'name.short',
                        label: 'Naam',
                        name:'Korte Naam',
                        sortable:'name_nickname',
                        visible:false
                    },
                    {
                        key:'birth_date',
                        label:'Geboortedatum',
                        name:'Geboortedatum',
                        sortable:'birth_date',
                        visible:true,
                    },
                    {
                        key:'membership_status',
                        label:'Status Lidmaatschap',
                        name:'Lidstatus',
                        sortable:'membership_status',
                        visible:true
                    },
                    {
                        key:'links',
                        label:'',
                        name:'Actieknoppen',
                        visible:true,
                    }
                ],
            }
        },

        methods:{

        }

    }
</script>

<style scoped>

</style>