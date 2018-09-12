import gql from 'graphql-tag';



export const SearchTablePagination = gql`
        fragment SearchTablePagination on Pagination {
            from
            to
            total
        }
    `
;

export const BaseAvatar = gql`
    fragment BaseAvatar on Avatar {
        image
        letters
    }
`;

export const PersonNameSpan = gql`
    fragment PersonNameSpan on Person {
        name_initials
        name_first
        name_middle
        name_prefix
        name_last
        name_nickname
    }
`;

export const PersonMembershipStatus = gql`
    fragment PersonMembershipStatus on Person {
        membership_status
        membership_status_since
    }
`;

export const PersonBirthDate = gql`    
    fragment PersonBirthDate on Person {
        birth_date
        age
    }
`;

export const PersonAvatar = gql`
    fragment PersonAvatar on Person {
        avatar {
            ...BaseAvatar
        }
    }
    ${BaseAvatar}
`;

export const UserAvatar = gql`
    fragment UserAvatar on User {
        avatar {
            ...BaseAvatar
        }
    }
    ${BaseAvatar}
`;



export const GroupTag = gql`
    fragment GroupTag on Group {
        name
        name_short
        member_name
        description
        category {
            id
            style
        }
    }
`;


export const GroupCategorySpan = gql`
    fragment GroupCategorySpan on GroupCategory {
        name
        name_short
        description
        style
    }
`;


export default {
    SearchTablePagination,
    BaseAvatar,
    PersonNameSpan, PersonMembershipStatus, PersonBirthDate, PersonAvatar,
    UserAvatar,
    GroupTag,
    GroupCategorySpan
};