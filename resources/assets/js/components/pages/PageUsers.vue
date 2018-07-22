<template>

    <div>
        <b-container>

            <search-per-page-input v-model="perPage" />

            <b-card no-body>
                <b-table id="usersSearchTable" ref="searchTable" class="card-table"
                         :items="users.data"
                         :fields="fields"
                         :busy="$apollo.queries.users.loading"
                         :sort-by.sync="orderByField"
                         :sort-desc.sync="orderByDesc"
                         no-local-sorting
                >
                    <template slot="avatar" slot-scope="{ item }">
                        <base-avatar v-bind="item.avatar" size="md" />
                    </template>
                    <template slot="person" slot-scope="{ item }">
                        <div v-if="item.person">
                            <div>{{ item.person.name }}</div>
                            <div class="font-italic text-muted-dark small">
                                <display-membership-status :status="item.person.membership_status"
                                                            :since="item.person.membership_status_since"
                                />
                            </div>
                        </div>
                        <div v-else class="text-muted">(Niet gekoppeld)</div>
                    </template>
                </b-table>
            </b-card>

            <b-pagination :total-rows="users.total"
                          :per-page="users.per_page"
                          :limit="11"
                          v-model="page">
            </b-pagination>

        </b-container>
    </div>

</template>

<script>
    import getUsersForTableQuery from '../../queries/users.graphql';
    import SearchPerPageInput from "../SearchPerPageInput";
    import BaseAvatar from "../BaseAvatar";
    import DisplayMembershipStatus from "../DisplayMembershipStatus";

    export default {
        name: 'page-users',

        apollo: {
            users:{
                query:getUsersForTableQuery,
                variables() {
                    return {
                        page: this.page,
                        limit: this.perPage,
                        orderByField: this.orderByField,
                        orderByDirection: this.orderByDirection
                    };
                }
            }
        },

        data: function() {
            return {
                page:1,
                perPage:10,
                fields:[
                    {key:'avatar', label:''},
                    {key:'id',    sortable:true, label:'ID'},
                    {key:'name',  sortable:true, label:'Gebruikersnaam'},
                    {key:'email', sortable:true, label:'E-mailadres'},
                    {key:'person', label:'Persoon'}
                ],
                orderByField:'id',
                orderByDirection:'ASC',
                users:{}
            }
        },

        computed: {
            orderByDesc: {
                get() {
                    return this.orderByDirection === 'DESC';
                },
                set(newValue) {
                    if(newValue) {
                        this.orderByDirection = 'DESC';
                    } else {
                        this.orderByDirection = 'ASC';
                    }
                }
            }
        },


        components: {
            DisplayMembershipStatus,
            BaseAvatar,
            SearchPerPageInput
        }
    }
</script>

<style scoped>

</style>