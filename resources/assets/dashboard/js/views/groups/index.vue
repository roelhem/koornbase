<template>

    <div>

        <b-container>
            <tabler-page-header
                    title="Groepen"
                    :breadcrumb="[{icon:'database',text:'Administratie',active:true}, {text:'Groepen',active:true}]"
            />
        </b-container>

        <!-- START: Search Header -->
        <search-header-container v-bind="searchHeaderProps" v-on="searchHeaderListeners">
            <b-button variant="success" href="#">
                <base-icon :icon="{fa:'plus',fe:'plus'}"
                           :from="['fe','fa']"
                           class="mr-2" />
                Groep Toevoegen
            </b-button>
        </search-header-container>
        <!-- END: Search Header -->

        <b-container>
            <b-row>

                <b-col lg="4">

                    <div class="mb-2">
                        <b-button block>
                            <base-icon :icon="{fa:'pencil',fe:'edit'}"
                                       :from="['fe','fa']"
                                       class="mr-2" />
                            Groep-categori&euml;en bewerken&hellip;
                        </b-button>
                    </div>

                    <show-group-category-list-card :highlighted.sync="highlightedCategory"
                                                   v-model="selectedCategories"
                    />

                </b-col>

                <b-col lg="8">
                    <b-form-row class="mb-2">

                        <b-col lg="6">
                            <tabler-input-icon append="search">
                                <b-form-input type="search" placeholder="Zoeken..." v-model="search" />
                            </tabler-input-icon>
                        </b-col>

                        <b-col lg="4">
                            <search-sort-input v-bind="sortInputProps" v-on="sortInputListeners" />
                        </b-col>

                        <b-col>
                            <search-per-page-input v-model="perPage" />
                        </b-col>

                    </b-form-row>




                    <b-card no-body>
                        <b-table v-bind="bTableProps" v-on="bTableListeners" hover>

                            <template slot="category_id" slot-scope="{ item }">
                                <base-stamp :default-style="item.category.style"
                                            @mouseover="highlightedCategory = item.category.id"
                                            @mouseleave="highlightedCategory = null"
                                            :class="{'muted-stamp':getCategoryMuted(item.category.id)}"
                                />
                            </template>

                            <template slot="name" slot-scope="{ item }">
                                <span v-b-tooltip.hover.bottom="item.description || ''">{{ item.name }}</span>
                            </template>


                            <template slot="persons" slot-scope="{ item }">
                                <base-avatar-list :count="item.persons.total" :items="item.persons.data" :max="personLimit" stacked :key="`avatar-list-${item.id}`">
                                    <template slot="avatar" slot-scope="a">
                                        <base-avatar v-bind="a.item.avatar"
                                                     default-style="person-default"
                                                     v-b-tooltip.hover="a.item.name_short"
                                                     :key="`list-${item.id}-avatar-${a.item.id}`"
                                        />
                                    </template>
                                </base-avatar-list>
                            </template>

                            <template slot="actions" slot-scope="{ item }">
                                <router-link class="icon" :to="{name:'db.groups.view', params:{id:item.id}}">
                                    <base-icon icon="more-vertical" from="fe" />
                                </router-link>
                            </template>

                        </b-table>
                    </b-card>





                    <b-pagination v-bind="paginationProps" v-on="paginationListeners">
                    </b-pagination>


                </b-col>

            </b-row>
        </b-container>

    </div>



</template>

<script>

    import { GROUPS_INDEX } from "../../apis/graphql/queries";

    import TablerPageHeader from "../../components/layouts/title/TablerPageHeader";
    import TablerInputIcon from "../../components/layouts/forms/TablerInputIcon";
    import SearchSortInput from "../../components/features/table-search/SearchSortInput";
    import SearchPerPageInput from "../../components/features/table-search/SearchPerPageInput";
    import searchTableMixin from "../../mixins/searchTableMixin";
    import SearchHeaderContainer from "../../components/features/table-search/SearchHeaderContainer";
    import BaseIcon from "../../components/displays/BaseIcon";
    import BaseStamp from "../../components/displays/BaseStamp";
    import BaseAvatar from "../../components/displays/BaseAvatar";
    import BaseAvatarList from "../../components/displays/BaseAvatarList";
    import ShowGroupCategoryListCard from "../../components/displays/ShowGroupCategoryListCard";

    export default {
        components: {
            ShowGroupCategoryListCard,
            BaseAvatarList,
            BaseAvatar,
            BaseStamp,
            BaseIcon,
            SearchHeaderContainer,
            SearchPerPageInput,
            SearchSortInput,
            TablerInputIcon,
            TablerPageHeader
        },

        name: "index",

        mixins: [searchTableMixin],

        searchTable: {
            queryKey:'groups',
            query:{
                query: GROUPS_INDEX,
                variables() {
                    return {
                        page:this.page,
                        limit:this.perPage,
                        personLimit:this.personLimit,
                        orderBy:this.sortBy,
                        orderDir:this.sortDir,
                        search:this.search,
                        filter:{
                            anyCategoryId:this.selectedCategories.length > 0 ? this.selectedCategories : null,
                        }
                    }
                }
            },
            defaults: {
                perPage:25,
                sortBy:'category_id',
            },
            columns:[
                {
                    key:'category_id',
                    label:'',
                    name:'Categorie',
                    visible:true,
                    sortable:true,
                    tdClass:'p-2',
                    thStyle:{'width':'1px'},
                },
                {
                    key:'id',
                    label:'ID',
                    visible:false,
                    sortable:true,
                },
                {
                    key:'name',
                    label:'Naam',
                    sortable:true,
                    visible:true
                },
                {
                    key:'name_short',
                    label:'Korte naam',
                    sortable:true,
                    visible:false
                },
                {
                    key:'member_name',
                    label:'Titel',
                    sortable:true,
                    visible:false
                },
                {
                    key:'persons',
                    label:'Groepsleden',
                    tdClass:'p-2',
                    sortable:false,
                    visible:true,
                },
                {
                    key:'actions',
                    label:'',
                    visible:true,
                    thStyle:{'width':'1px'}
                },
            ],
            recordsName:'groepen',
        },

        data: function() {
            return {
                search:null,
                highlightedCategory:null,
                selectedCategories:[],
                personLimit:8,
            };
        },

        methods: {
            getCategoryMuted(categoryId) {
                if(this.highlightedCategory === categoryId) {
                    return false;
                }

                if(this.selectedCategories.indexOf(categoryId) !== -1) {
                    return false;
                }

                return true;
            },
        }
    }
</script>

<style scoped>

    .muted-stamp {
        opacity: 0.5;
    }

</style>