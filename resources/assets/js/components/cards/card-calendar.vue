<template>

    <div class="card">
        <div class="card-header border-bottom-0">
            <div class="btn-group btn-group-sm">
                <a href="javascript:void(0);" class="btn" @click="bus.$emit('view-prev-year')"><i class="fe fe-chevrons-left"></i></a>
                <a href="javascript:void(0);" class="btn" @click="bus.$emit('view-prev')"><i class="fe fe-chevron-left"></i></a>
                <a href="javascript:void(0);" class="btn" @click="bus.$emit('view-today')"><i class="fe fe-circle"></i></a>
                <a href="javascript:void(0);" class="btn" @click="bus.$emit('view-next')"><i class="fe fe-chevron-right"></i></a>
                <a href="javascript:void(0);" class="btn" @click="bus.$emit('view-next-year')"><i class="fe fe-chevrons-right"></i></a>
            </div>
            <h3 class="card-title">{{ title }}</h3>
            <div class="card-options">
                <div class="btn-group btn-group-sm">
                    <button v-for="view in views" class="btn btn-secondary"
                            :class="view.name === currentView ? 'active' : ''"
                            @click="currentView = view.name">{{ view.label }}</button>
                </div>
            </div>
        </div>


        <full-calendar @view-render="onViewRender"
                       :bus="bus"
                       :view="currentView"
                       :events="events" :sources="sources">
        </full-calendar>

    </div>


</template>

<script>
    export default {
        name: "card-calendar",

        props: {
            views: {
                type:Array,
                default:function() {
                    return [
                        {
                            'name':'agendaWeek',
                            'label':'Week'
                        },
                        {
                            'name':'month',
                            'label':'Maand',
                        },
                        {
                            'name':'listYear',
                            'label':'Jaar',
                        },

                    ];
                }
            },
            defaultView: {
                type:String,
                default:function() {
                    return 'month';
                }
            },
            events: {
                type:Array,
                default:function() {
                    return [];
                }
            },
            sources: {
                type:Array,
                default:function() {
                    return [];
                }
            }
        },

        data: function() {
            return {
                title: 'Laden...',
                currentView: this.defaultView,
                bus: new Vue()
            };
        },

        methods: {
            onViewRender: function(view, element) {
                this.title = view.title;
                this.currentView = view.name;
            }
        }

    }
</script>

<style scoped>

</style>