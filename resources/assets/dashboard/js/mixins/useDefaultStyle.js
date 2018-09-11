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

        getNamedStyle() {
            let style = this.getStyleFromName(arguments[1]);
            if(style === undefined) {
                style = this.defaultStyleObject();
            }
            let keys = arguments.slice(1);
            return objectPath.coalesce(style, keys);
        }
    }

};