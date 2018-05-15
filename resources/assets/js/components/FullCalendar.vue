<template>

    <div></div>

</template>

<script>
    import axios from 'axios';
    import eventRenderer from '../calendars/event-rendering';

    export default {

        name: "full-calendar",

        props: {
            bus:{
                type:Vue,
                default:function() {
                    return new Vue();
                }
            },
            view: {
                type:String,
                default:function() {
                    return 'month';
                }
            },
            events:{
                type:Array,
                default:function() {
                    return [];
                }
            },
            sources:{
                type:Array,
                default:function() {
                    return [];
                }
            }
        },

        methods: {
            fc(...args) {
                return $(this.$el).fullCalendar(...args);
            },
            createEventSource(src) {
                return {
                    events: function(start, end, timezone, callback) {
                        axios.get(src, {
                            params: {'start': start.toISOString(), 'end': end.toISOString()}
                        }).then(response => {
                            callback(response.data.data);
                        }).catch(e => {
                            console.log(e);
                        })
                    }
                }
            }
        },

        computed: {
            eventSources: function() {
                let res = [];
                for(let i = 0; i < this.sources.length; i++) {
                    console.log(i);
                    res[i] = this.createEventSource(this.sources[i]);
                }

                return res;
            },
            config: function() {
                const self = this;
                return {
                    header:false,
                    themeSystem:'bootstrap4',
                    locale: 'nl',
                    events: this.events,
                    eventSources: this.eventSources,
                    eventRender: eventRenderer,

                    viewRender:function(view, element) {
                        self.$emit('view-render', view, element);
                    }
                };
            },
        },

        watch: {
            view: function(newViewName) {
                this.fc('changeView',newViewName);
            }
        },


        mounted() {
            console.log(this.config);
            console.log(this.sources);

            const self = this;

            this.bus.$on('view-prev', () => self.fc('prev'));
            this.bus.$on('view-today', () => self.fc('today'));
            this.bus.$on('view-next', () => self.fc('next'));
            this.bus.$on('view-prev-year', () => self.fc('prevYear'));
            this.bus.$on('view-next-year', () => self.fc('nextYear'));

            // Initialize the FullCalendar
            this.fc(this.config);
        }
    }
</script>

<style scoped>

</style>