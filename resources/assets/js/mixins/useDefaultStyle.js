import styles from '../constants/styles';
import objectPath from 'object-path';

export default {


    props: {
        defaultStyle:{
            type:[Object,String]
        },
    },

    computed: {

        defaultStyleObject: function() {
            if(typeof this.defaultStyle === 'string') {
                return this.getStyleFromName(this.defaultStyle);
            } else if(this.defaultStyle) {
                return this.defaultStyle;
            } else {
                return {};
            }
        },

    },


    methods: {
        getStyleFromName(name) {
            return styles.find(style => style.name === name);
        },

        getStyle() {
            return objectPath.coalesce(this.defaultStyleObject, arguments);
        },
    }

};