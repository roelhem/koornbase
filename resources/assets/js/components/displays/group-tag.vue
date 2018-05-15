<template>

    <span class="tag group-tag" :class="g.category.style.tagColor ? 'tag-'+g.category.style.tagColor : null" slot="reference">

        <group-avatar v-if="avatar && g.category.style.avatar" :config="g.category.style.avatar"></group-avatar>

        <span v-if="labelType === 'short'">{{ g.name_short }}</span>
        <span v-else-if="labelType === 'member_name'">{{ g.member_name }}</span>
        <span v-else>{{ g.name }}</span>

        <slot>

        </slot>
    </span>

</template>

<script>
    import axios from 'axios';
    import GroupAvatar from "./group-avatar";

    export default {
        name: "group-tag",
        props: {
            id: {
                default:false
            },
            avatar:{
                default:true
            },
            labelType:{
                type:String,
                default:'short'
            },
            group:{
                default:function() {
                    return {
                        name: 'Groep laden...',
                        name_short: 'Laden...',
                        member_name: 'Laden...',
                        description: 'Nog bezig met het laden van de groep...',
                        category: {
                            style: {
                                tagColor: null,
                                avatar: {
                                    color: null,
                                    icon: 'refresh',
                                    spin: true,
                                }
                            }
                        }
                    };
                }
            }
        },
        data: function() {
            return {
                g:this.group
            }
        },
        methods: {
            refresh() {

                axios.get('/display/group/'+this.id).then(response => {
                    let group = response.data.data;

                    this.g = group;

                }).catch(error => {
                    console.log(error);
                });

            }
        },
        created() {
            if(this.id) {
                this.refresh();
            }
        },
        components: {
            GroupAvatar,
            'popper':Popper
        }
    }
</script>
