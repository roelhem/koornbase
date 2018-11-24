import gql from "graphql-tag";

const CREATE_OAUTH_CLIENT_MUTATIONS = {

    createOAuthAuthCodeClient:gql`
        mutation createOAuthAuthCodeClient($name:String! $redirect:String! $userId:ID) {
            createOAuthAuthCodeClient(name:$name redirect:$redirect userId:$userId) {
                id
            }
        }
    `,

    createOAuthPasswordClient:gql`
        mutation createOAuthPasswordClient($name:String! $redirect:String $userId:ID) {
            createOAuthPasswordClient(name:$name redirect:$redirect userId:$userId) {
                id
            }
        }
    `,


    createOAuthPersonalClient:gql`
        mutation createOAuthPersonalClient($name:String! $userId:ID) {
            createOAuthPersonalClient(name:$name userId:$userId) {
                id
            }
        }
    `,

    createOAuthCredentialsClient:gql`
        mutation createOAuthCredentialsClient($name:String! $userId:ID) {
            createOAuthCredentialsClient(name:$name userId:$userId) {
                id
            }
        }
    `

};