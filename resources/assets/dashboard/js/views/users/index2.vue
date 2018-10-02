<template>

    <b-container>
        <b-card no-body>
            <b-table class="card-table"
                     show-empty
                     :items="items"
                     :fields="tableFields"
            >
                <template slot="avatar" slot-scope="{item}">
                    <user-avatar :user="item" size="md" />
                </template>


                <template slot="person" slot-scope="{ item }">
                    <template v-if="item.person !== null">
                        <div><span-person-name :person-name="item.person.name" /></div>
                        <div class="small text-muted-dark">
                            <span-membership-status
                                    :membership-status="item.person.membershipStatus"
                                    date-size="sm"
                            />
                        </div>
                    </template>
                    <template v-else>
                        <div class="text-muted font-italic">( Geen gekoppeld persoon. )</div>
                    </template>
                </template>


            </b-table>
        </b-card>

        <b-button @click="showMore()">Meer...</b-button>
    </b-container>

</template>

<script>
    import gql from "graphql-tag";
    import UserAvatar from "../../components/displays/UserAvatar";
    import SpanPersonName from "../../components/displays/spans/SpanPersonName";
    import SpanMembershipStatus from "../../components/displays/spans/SpanMembershipStatus";

    const pageSize = 10;

    const tableFields = [
        { key:"avatar", label:"", name:"Avatar", visible:true, thStyle:{'width':'1px'} },
        { key:"id", label:"ID", visible:false, sortable:true },
        { key:"name", label:"Gebruiker", sortName: 'Gebruikersnaam', visible:true, sortable:true },
        { key:"email", label:"E-mail", visible:false, sortable:true },
        { key:"person", label:"Persoon", visible: true, sortable:true },
        { key:'links', label:'', name:'Actieknoppen', visible:true, thStyle:{'width':'1px'}, },
    ];

    export default {
        components: {
            SpanPersonName,
            SpanMembershipStatus,
            UserAvatar
        },
        name: "page-users",

        apollo: {
            users: {
                query:gql`
                    query indexUsers($cursor:Cursor $pageSize:Int!) {
                        users(first:$pageSize after:$cursor orderBy:id_ASC) {
                            pageInfo {
                                hasNextPage
                                endCursor
                            }
                            edges {
                                cursor
                                node {
                                    id
                                    name
                                    email
                                    ...UserAvatar
                                    person {
                                        id
                                        name { ...SpanPersonName }
                                        membershipStatus { ...SpanMembershipStatus }
                                    }
                                }
                            }
                        }
                    }
                    ${UserAvatar.fragment}
                    ${SpanPersonName.fragment}
                    ${SpanMembershipStatus.fragment}
                `,
                variables: {
                    cursor:null,
                    pageSize
                }
            }

        },

        data() {
            return {
                tableFields,
                users:{
                    pageInfo: {
                        hasNextPage:null,
                        endCursor:null
                    },
                    edges:[]
                }
            };
        },

        computed: {
            items() {
                return this.users.edges.map(edge => edge.node);
            }
        },

        methods: {
            showMore() {
                let cursor = this.users.pageInfo.endCursor;

                this.$apollo.queries.users.fetchMore({
                    variables: { cursor, pageSize },
                    updateQuery: (previousResult, {fetchMoreResult}) => {
                        return {
                            users: {
                                __typename: previousResult.users.__typename,
                                edges: [...previousResult.users.edges, ...fetchMoreResult.users.edges],
                                pageInfo: fetchMoreResult.users.pageInfo
                            },
                        };
                    }
                });
            }
        }
    }
</script>

<style scoped>

</style>