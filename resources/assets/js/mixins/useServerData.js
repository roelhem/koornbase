import axios from 'axios';

export default {

    data: function() {
        return {
            isLoading:false,
            hasError:false,
            serverData:null,
            serverMetaData:null
        }
    },

    computed: {
        serverData_config: function() {
            return {
                endpoint:null,
                params:null
            };
        },

        serverData__endpoint: function() {
            if(this.serverData_config) {
                const endpoint = this.serverData_config.endpoint;
                if(Array.isArray(endpoint)) {
                    return endpoint.join('/');
                } else if(typeof endpoint === 'string') {
                    return endpoint;
                }
            }
            return '';
        },

        serverData__params: function() {
            if(this.serverData_config && this.serverData_config.params) {
                return this.serverData_config.params;
            } else {
                return {};
            }
        }
    },

    methods: {
        loadServerData() {

            this.isLoading = true;


            axios.get(this.serverData__endpoint, {
                params: this.serverData__params
            }).then(response => {
                this.$emit('load-success', response);

                this.serverData = response.data.data;
                if(response.data.meta) {
                    this.serverMetaData = response.data.meta;
                }

                this.hasError = false;
                this.isLoading = false;

            }).catch(error => {
                console.log(error);
                this.hasError = true;
                this.$emit('load-failure', error);
                this.isLoading = false;
            });
        }
    }

};