import gql from "graphql-tag";



export const columns = {
    id: {
        key: "id",
        label: "ID"
    },
    index: {
        key: "index",
        label: "Volgnr",
        name: "Volgnummer",
        formatter(value) {
            return value + 1;
        }
    },
    label: {
        key: "label",
        label: "Label"
    },
    email_address: {
        key: "email_address",
        label: "E-mailadres"
    }
};

export const fragment = gql`
    fragment tablePersonEmailAddress on PersonEmailAddress {
        id
        index
        label
        email_address
    }
`;




export default {
    columns,
    fragment
};