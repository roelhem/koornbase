<template>

    <div>

        <b-container>
            <tabler-page-header
                    title="Applicaties"
                    :breadcrumb="[{icon:'cloud', text:'OAuth Server', active:true}, {text:'Applicaties',active:true}]"
            />
        </b-container>

        <search-header-container
                v-model="page"
                :is-loading="isLoading"
                :from="apps.from"
                :to="apps.to"
                :total="apps.total"
                :per-page="perPage"
                records-name="apps"
        >

        </search-header-container>

        <b-container>

            <b-card no-body>
                <b-table id="searchAppsTable" class="card-table"
                         :items="apps.data"
                         :busy="isLoading"
                         no-local-sorting
                >

                </b-table>
            </b-card>
        </b-container>

        <b-container>
            <b-card>
                <create-app-form />
            </b-card>
        </b-container>
    </div>
</template>

<script>
    import SearchHeaderContainer from "../../../components/features/table-search/SearchHeaderContainer";
    import { APPS_INDEX } from "../../../apis/graphql/queries";
    import CreateAppForm from "../../../components/features/crud/CreateAppForm";
    import TablerPageHeader from "../../../components/layouts/title/TablerPageHeader";

    export default {

        components: {
            TablerPageHeader,
            CreateAppForm,
            SearchHeaderContainer},
        apollo: {
            apps: {
                query: APPS_INDEX,
                variables() {
                    return {
                        page: this.page,
                        perPage: this.perPage
                    }
                }
            },
        },

        data:function() {
            return {
                page:1,
                perPage:10,
                apps:{
                    from:0,
                    to:0,
                    total:0,
                    data:[]
                }
            }
        },

        computed: {
            isLoading() {
                return this.$apollo.queries.apps.loading;
            }
        }
    }
</script>

<style scoped>

</style>