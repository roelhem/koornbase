/**
 *   APOLLO
 *
 *  This collection of javascript-applications handles almost all the communication with the backend. It is
 *  especially used for all the communication where the GraphQL-API is used.
 *  Besides of the communication, it also handles the cashing and predicting of the data from the backend.
 */

// The Main Vue component
import Vue from 'vue';

// The Apollo-libraries
import { ApolloClient } from 'apollo-client';
import { HttpLink } from 'apollo-link-http';
import { InMemoryCache, IntrospectionFragmentMatcher } from 'apollo-cache-inmemory';
import VueApollo from 'vue-apollo';
import introspectionQueryResultData from './fragmentTypes.json';


// Loading VueApollo into Vue as a plug-in.
Vue.use(VueApollo);



// Getting the csrf-token.
import { csrfToken } from "../../utils/tokens";
// The connection with the Backend.
export const link = new HttpLink({
    url:'/graphql',
    credentials:'include',
    headers: {
        "X-CSRF-TOKEN":      csrfToken,
        "X-Requested-With": "XMLHttpRequest"
    }
});




// Handles the storing of the cached data.
const fragmentMatcher = new IntrospectionFragmentMatcher({
    introspectionQueryResultData
});
export const cache = new InMemoryCache({
    fragmentMatcher
});





// The JavaScript-client for GraphQL-requests.
export const client = new ApolloClient({
    link,
    cache,
    connectToDevTools:true,
});



// The provider for Vue of the Apollo-libraries.
export const provider = new VueApollo({
    defaultClient: client,
});



// The provider is also the default export of this file.
export default provider;