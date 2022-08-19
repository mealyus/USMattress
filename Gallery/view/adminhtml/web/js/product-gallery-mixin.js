define([
    'jquery',
    'underscore',
    'mage/template',
    'uiRegistry'    
], function($, _, mageTemplate, registry){
    'use strict';
    return function (widget) {
        $.widget('mage.productGallery', widget, {
            options: {
                imageSelector: '[data-role=image]',
                imageElementSelector: '[data-role=image-element]',
                template: '[data-template=image]',
                imageResolutionLabel: '[data-role=resolution]',
                imgTitleSelector: '[data-role=img-title]',
                imgCaptionSelector: '[data-role=image-image_caption]',
                imgClassSelector: '[data-role=image-caption_class]',
                imageSizeLabel: '[data-role=size]',
                types: null,
                initialized: false
            },

            /**
             * Gallery creation
             * @protected
             */
            _create: function () {
                this.options.types = this.options.types || this.element.data('types');
                this.options.images = this.options.images || this.element.data('images');
                this.options.parentComponent = this.options.parentComponent || this.element.data('parent-component');
                if (typeof this.element.find(this.options.template).html() !== 'undefined') {
                    this.imgTmpl = mageTemplate(this.element.find(this.options.template).html().trim());
                }
                

                this._bind();

                $.each(this.options.images, $.proxy(function (index, imageData) {
                    this.element.trigger('addItem', imageData);
                }, this));

                this.options.initialized = true;
            },

            /**
             *
             * @param {jQuery.Event} event
             * @param {Object} data
             */
            _updateImageCaption: function (event, data) {
                var imageData = data.imageData,
                    $imgContainer = this.findElement(imageData),
                    $title = $imgContainer.find(this.options.imgCaptionSelector),
                    value;
                value = imageData.image_caption;
                $title.val(value);
                this._contentUpdated();
            },

            /**
             *
             * @param {jQuery.Event} event
             * @param {Object} data
             */
            _updateImageClass: function (event, data) {
                var imageData = data.imageData,
                    $imgContainer = this.findElement(imageData),
                    $title = $imgContainer.find(this.options.imgClassSelector),
                    value;

                value = imageData.caption_class;

                $title.val(value);

                this._contentUpdated();
            },

            /**
             * Bind handler to elements
             * @protected
             */
            return (_bind) {
                this._on({
                    updateImageTitle: '_updateImageTitle',
                    updateImageCaption: '_updateImageCaption',
                    updateImageClass: '_updateImageClass',
                    updateVisibility: '_updateVisibility',
                    openDialog: '_onOpenDialog',
                    addItem: '_addItem',
                    removeItem: '_removeItem',
                    setImageType: '_setImageType',
                    setPosition: '_setPosition',
                    resort: '_resort',
                });
            }
        });
        
        return $.mage.productGallery;
    }
});