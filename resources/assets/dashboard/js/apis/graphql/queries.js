import gql from 'graphql-tag';

import fragments from "./queries/fragments";

// ---------------------------------------------------------------------------------------------------------------- //
// ------- GLOBAL ------------------------------------------------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------- //


export const CURRENT_USER = gql`
    query currentUser {
        currentUser:me {
            id
            name
            ...UserAvatar
            person {
                id
                ...PersonNameSpan
            }
            email
        }
    }
    ${fragments.UserAvatar}
    ${fragments.PersonNameSpan}
`;

// ---------------------------------------------------------------------------------------------------------------- //
// ------- PERSONS ------------------------------------------------------------------------------------------------ //
// ---------------------------------------------------------------------------------------------------------------- //

export const PERSONS_INDEX = gql`
    query personsIndex(
        $page:Int = 1,
        $limit:Int = 10,
        $orderBy:Person_orderField = name,
        $orderDir:SortOrderDirection = ASC,
        $filter:Person_filter,
        $search:String
    ) {
        persons(page:$page, limit:$limit, orderBy:[{by:$orderBy, dir:$orderDir}], filter:$filter, search:$search) {
            ...SearchTablePagination
            data {
                id
                ...PersonNameSpan
                ...PersonBirthDate
                ...PersonMembershipStatus
                ...PersonAvatar
            }
        }
    }
    ${fragments.SearchTablePagination}
    ${fragments.PersonNameSpan}
    ${fragments.PersonBirthDate}
    ${fragments.PersonMembershipStatus}
    ${fragments.PersonAvatar}
`;

export const PERSONS_VIEW = gql`
    query personsView($id:ID!) {
        person(id:$id) {
            id
            ...PersonNameSpan
            ...PersonBirthDate
            ...PersonMembershipStatus
            ...PersonAvatar
            groups(limit:10) {
                total
                data {
                    id
                    ...GroupTag
                }
            }
        }
    }
    ${fragments.PersonAvatar}
    ${fragments.PersonNameSpan}
    ${fragments.PersonBirthDate}
    ${fragments.PersonMembershipStatus}
    ${fragments.GroupTag}
`;

// ---------------------------------------------------------------------------------------------------------------- //
// ------- GROUPS ------------------------------------------------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------- //

export const GROUPS_INDEX = gql`
    query groupsIndex (
        $page:Int = 1,
        $limit:Int = 25,
        $orderBy:Group_orderField = name,
        $orderDir:SortOrderDirection = ASC,
        $filter:Group_filter,
        $search:String
        $personLimit:Int = 10
    ) {
        groups(page:$page, limit:$limit, orderBy:[{by:$orderBy, dir:$orderDir}], filter:$filter, search:$search) {
            ...SearchTablePagination
            data {
                name
                name_short
                description
                member_name
                id
                category {
                    id
                    name_short
                    style
                }
                persons(limit:$personLimit) {
                    total
                    data {
                        id
                        name
                        name_short
                        ...PersonAvatar
                    }
                }
            }
        }
    }
    ${fragments.SearchTablePagination}
    ${fragments.PersonAvatar}
`;

export const GROUPS_VIEW = gql`
    query groupsView($id:ID!) {
        group(id:$id) {
            id
            slug
            name
            name_short
            description
            member_name
            is_required
    
            category {
                id
                ...GroupCategorySpan
            }
    
            persons {
                from
                to
                total
                data {
                    id
                    ...PersonNameSpan
                    ...PersonAvatar
                    ...PersonMembershipStatus
                }
            }
    
            emailAddresses {
                from
                to
                total
                data {
                    id
                    email_address
                    remarks
                }
            }
    
        }
    }
    ${fragments.GroupCategorySpan}
    ${fragments.PersonNameSpan}
    ${fragments.PersonAvatar}
    ${fragments.PersonMembershipStatus}
`;

// ---------------------------------------------------------------------------------------------------------------- //
// ------- USERS -------------------------------------------------------------------------------------------------- //
// ---------------------------------------------------------------------------------------------------------------- //

export const USERS_INDEX = gql`
    query usersIndex(
        $page: Int = 1,
        $limit: Int = 10,
        $search:String,
        $filter: User_filter,
        $orderBy: User_orderField = id,
        $orderDir: SortOrderDirection = ASC,
    ) {
        users(
            page: $page,
            limit: $limit,
            search:$search,
            filter:$filter,
            orderBy: [{by: $orderBy, dir: $orderDir}]
        ) {
            ...SearchTablePagination
            data {
                id
                ...UserAvatar
                person {
                    id
                    name
                    ...PersonMembershipStatus
                }
                name
                email
                created_at
                updated_at
            }
        }
    }
    ${fragments.UserAvatar}
    ${fragments.PersonMembershipStatus}
    ${fragments.SearchTablePagination}
`;

// ---------------------------------------------------------------------------------------------------------------- //
// ------- OAUTH_CLIENTS ------------------------------------------------------------------------------------------ //
// ---------------------------------------------------------------------------------------------------------------- //

export const APPS_INDEX = gql`query appsIndex(
    $page:Int = 1,
    $limit:Int = 10
    ) {
        apps(
            page:$page,
            limit:$limit
        ) {
            ...SearchTablePagination
            data {
                id
                name
                name_short
                description
            }
        }
    }
    ${fragments.SearchTablePagination}
`;

// ---------------------------------------------------------------------------------------------------------------- //
// ------- OAUTH_CLIENTS ------------------------------------------------------------------------------------------ //
// ---------------------------------------------------------------------------------------------------------------- //

export const OAUTH_CLIENTS_INDEX = gql`
    query oAuthClientsIndex(
        $page:Int = 1,
        $limit:Int = 10,
        $orderBy:OAuthClient_orderField = name,
        $filter:OAuthClient_filter,
        $orderDir:SortOrderDirection = ASC,
    ) {
        clients: oAuthClients(page:$page, limit:$limit, filter:$filter, orderBy:[{by:$orderBy, dir:$orderDir}]) {
            ...SearchTablePagination
            data {
                id
                name
                created_at
                updated_at
                redirect
                type
                revoked
                user {
                    id
                    name
                    email
                    ...UserAvatar
                    person {
                        id
                        ...PersonNameSpan
                    }
                }
            }
        }
    }
    ${fragments.SearchTablePagination}
    ${fragments.PersonNameSpan}
    ${fragments.UserAvatar}
`;