import gql from "graphql-tag";

export const AUTH_CODE = "AUTH_CODE";
export const AUTH_CODE_CLIENT_TYPE = {
    key:AUTH_CODE,
    default:true,
    name:"Authorization Code Client",
    shortName:"Auth Code",
    color:"lime",
    description:"Vraagt eerst om toestemming van de gebruiker (via de KoornBase website).",
    hasRedirect:true,
    requireRedirect:true,
};

export const PASSWORD = "PASSWORD";
export const PASSWORD_CLIENT_TYPE = {
    key:PASSWORD,
    name:"Password Client",
    shortName:"Password",
    color:"orange",
    description:"Kan direct tokens opvragen met het wachtwoord van de gebruiker.",
    hasRedirect:true,
    requireRedirect:false,
};

export const PERSONAL = "PERSONAL";
export const PERSONAL_CLIENT_TYPE = {
    key:PERSONAL,
    name:"Personal Access Client",
    shortName:"Personal",
    color:"cyan",
    description:"Genereert tokens voor individuele gebruikers. Bedoelt voor ontwikkelaars!",
    hasRedirect:false
};

export const CREDENTIALS = "CREDENTIALS";
export const CREDENTIALS_CLIENT_TYPE = {
    key:CREDENTIALS,
    name:"Client Credentials Client",
    shortName:"Credentials",
    color:"gray",
    description:"Voor comunicatie tussen servers, zonder aanmelden met een gebruiker.",
    hasRedirect:false
};

export default [AUTH_CODE_CLIENT_TYPE, PASSWORD_CLIENT_TYPE, PERSONAL_CLIENT_TYPE, CREDENTIALS_CLIENT_TYPE];