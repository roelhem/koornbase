/**
 *   UTILITIES: Token-helpers
 *
 *  This documents exports the tokens that were send by the Backend or other servers.
 */


/**
 * CSRF-TOKEN - This is the token send by the Backend in the header to ensure that the session can't be stolen by
 *              malicious client-side scripts from other websites. It has to be send alongside every POST-request
 *              to the server.
 */
export const csrfMetaTag = document.head.querySelector('meta[name="csrf-token"]');
if(!csrfMetaTag) {
    console.error("The CSRF-token couldn't be found in the header. https://laravel.com/docs/csrf#csrf-x-csrf-token");
}
export const csrfToken = csrfMetaTag.content;