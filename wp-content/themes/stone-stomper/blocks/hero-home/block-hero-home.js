/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/block-assets/components/ContainerBlock.jsx":
/*!********************************************************!*\
  !*** ./src/block-assets/components/ContainerBlock.jsx ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ContainerBlockContent: () => (/* binding */ ContainerBlockContent),
/* harmony export */   customAttributes: () => (/* binding */ customAttributes),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _DesignOption_jsx__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./DesignOption.jsx */ "./src/block-assets/components/DesignOption.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__);





const ContainerBlock = ({
  children,
  props,
  customClass = '',
  hideWrapper = false
}) => {
  const {
    attributes,
    setAttributes
  } = props;
  const myCustomWidthClass = attributes.bgWidth ? 'content-width-border ctn editor-' + attributes.bgWidth : '';
  const myCustomBorderClass = attributes.designBorder ? 'editor-' + attributes.designBorder : '';
  const myCustomDesignClass = attributes.bgDesignType ? 'editor-' + attributes.bgDesignType : '';
  const myCustomShapeClass = attributes.bgShape ? 'editor-' + attributes.bgShape : '';
  const myCustomBoxClass = attributes.hasBoxedBackground ? '' : 'without-boxed';
  const myCustomOverlapClass = attributes.hasOverlap ? 'editor-block-overlap' : '';
  const ContainerClasses = [myCustomDesignClass, myCustomWidthClass, myCustomBorderClass, myCustomShapeClass, myCustomBoxClass, myCustomOverlapClass, customClass].join(' ');
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__.InspectorControls, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Panel, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelBody, {
          title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)('Container'),
          initialOpen: true,
          children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
            className: "theme-containers container-width",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("h2", {
                children: "Content Width"
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
                value: attributes.bgWidth,
                className: "dc-sidebar-select",
                options: [{
                  label: '1410px (Default)',
                  value: 'ctn'
                }, {
                  label: '1920px',
                  value: 'ctn-1920'
                }, {
                  label: '1790px',
                  value: 'ctn-1790'
                }, {
                  label: '1300px',
                  value: 'ctn-1300'
                }, {
                  label: '1160px',
                  value: 'ctn-1160'
                }, {
                  label: '960px',
                  value: 'ctn-960'
                }, {
                  label: '690px',
                  value: 'ctn-690'
                }, {
                  label: 'Full Width',
                  value: 'ctn-full-width'
                }],
                onChange: value => setAttributes({
                  bgWidth: value
                })
              })
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("hr", {}), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
            className: "theme-containers container-design",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("h2", {
                children: "Background Color"
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_DesignOption_jsx__WEBPACK_IMPORTED_MODULE_3__["default"], {
                props: props,
                value: attributes.bgDesignType,
                DesignKey: "bgDesignType",
                help: "",
                options: [{
                  label: 'Light Pink ',
                  value: 'ctn-light-pink',
                  display: '#ffffff'
                }, {
                  label: 'Brown',
                  value: 'ctn-brown',
                  display: '#C3B8A6'
                }]
              })
            })]
          }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("hr", {}), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)("div", {
            className: "theme-containers container-design",
            children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("h2", {
                children: "Container Margin"
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
                value: attributes.marginTop,
                label: "Top",
                className: "dc-sidebar-select",
                options: [{
                  label: '96px (Default)',
                  value: ''
                }, {
                  label: '2px',
                  value: 'gl-s2'
                }, {
                  label: '4px',
                  value: 'gl-s4'
                }, {
                  label: '6px',
                  value: 'gl-s6'
                }, {
                  label: '8px',
                  value: 'gl-s8'
                }, {
                  label: '12px',
                  value: 'gl-s12'
                }, {
                  label: '16px',
                  value: 'gl-s16'
                }, {
                  label: '20px',
                  value: 'gl-s20'
                }, {
                  label: '24px',
                  value: 'gl-s24'
                }, {
                  label: '30px',
                  value: 'gl-s30'
                }, {
                  label: '36px',
                  value: 'gl-s36'
                }, {
                  label: '48px',
                  value: 'gl-s48'
                }, {
                  label: '64px',
                  value: 'gl-s64'
                }, {
                  label: '72px',
                  value: 'gl-s72'
                }, {
                  label: '96px',
                  value: 'gl-s96'
                }, {
                  label: '110px',
                  value: 'gl-s110'
                }, {
                  label: '128px',
                  value: 'gl-s128'
                }, {
                  label: '156px',
                  value: 'gl-s156'
                }, {
                  label: '196px',
                  value: 'gl-s196'
                }, {
                  label: '200px',
                  value: 'gl-s200'
                }, {
                  label: '236px',
                  value: 'gl-s236'
                }],
                onChange: value => setAttributes({
                  marginTop: value
                })
              })
            }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.PanelRow, {
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
                value: attributes.marginBottom,
                label: "Bottom",
                className: "dc-sidebar-select",
                options: [{
                  label: '96px (Default)',
                  value: ''
                }, {
                  label: '2px',
                  value: 'gl-s2'
                }, {
                  label: '4px',
                  value: 'gl-s4'
                }, {
                  label: '6px',
                  value: 'gl-s6'
                }, {
                  label: '8px',
                  value: 'gl-s8'
                }, {
                  label: '12px',
                  value: 'gl-s12'
                }, {
                  label: '16px',
                  value: 'gl-s16'
                }, {
                  label: '20px',
                  value: 'gl-s20'
                }, {
                  label: '24px',
                  value: 'gl-s24'
                }, {
                  label: '30px',
                  value: 'gl-s30'
                }, {
                  label: '36px',
                  value: 'gl-s36'
                }, {
                  label: '48px',
                  value: 'gl-s48'
                }, {
                  label: '64px',
                  value: 'gl-s64'
                }, {
                  label: '72px',
                  value: 'gl-s72'
                }, {
                  label: '96px',
                  value: 'gl-s96'
                }, {
                  label: '110px',
                  value: 'gl-s110'
                }, {
                  label: '128px',
                  value: 'gl-s128'
                }, {
                  label: '156px',
                  value: 'gl-s156'
                }, {
                  label: '196px',
                  value: 'gl-s196'
                }, {
                  label: '200px',
                  value: 'gl-s200'
                }, {
                  label: '236px',
                  value: 'gl-s236'
                }],
                onChange: value => setAttributes({
                  marginBottom: value
                })
              })
            })]
          })]
        })
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      className: 'dc-spacer ' + attributes.marginTop
    }), hideWrapper ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("section", {
      className: ContainerClasses,
      children: children
    }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("section", {
      className: ContainerClasses,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
        className: "wrapper",
        children: children
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      className: 'dc-spacer ' + attributes.marginBottom
    })]
  });
};
const ContainerBlockContent = ({
  children,
  props,
  customClass = '',
  hideWrapper = false
}) => {
  const {
    attributes
  } = props;
  const myCustomWidthClass = attributes.bgWidth ? attributes.bgWidth : 'ctn';
  const myCustomBorderClass = attributes.designBorder ? attributes.designBorder : ' ';
  const myCustomDesignClass = attributes.bgDesignType ? attributes.bgDesignType : '';
  const myCustomShapeClass = attributes.bgShape ? attributes.bgShape : '';
  const myCustomBoxClass = attributes.hasBoxedBackground ? '' : 'without-boxed';
  const myCustomOverlapClass = attributes.hasOverlap ? 'ctn-overlap' : '';
  const ContainerClasses = [myCustomDesignClass, myCustomWidthClass, myCustomBorderClass, myCustomShapeClass, myCustomBoxClass, myCustomOverlapClass, customClass].join(' ');
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.Fragment, {
    children: [attributes.marginTop ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      className: 'dc-spacer ' + attributes.marginTop
    }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.Fragment, {}), hideWrapper ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("section", {
      className: ContainerClasses,
      children: children
    }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("section", {
      className: 'ctn ' + ContainerClasses,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
        className: "wrapper",
        children: children
      })
    }), attributes.marginBottom ? /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)("div", {
      className: 'dc-spacer ' + attributes.marginBottom
    }) : /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.jsx)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_4__.Fragment, {})]
  });
};
const customAttributes = {
  bgDesignType: {
    type: 'string',
    default: ''
  },
  bgWidth: {
    type: 'string',
    default: 'ctn'
  },
  designBorder: {
    type: 'string',
    default: ''
  },
  marginTop: {
    type: 'string',
    default: ''
  },
  marginBottom: {
    type: 'string',
    default: ''
  },
  hasBoxedBackground: {
    type: 'boolean',
    default: false
  },
  hasOverlap: {
    type: 'boolean',
    default: false
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ContainerBlock);


/***/ }),

/***/ "./src/block-assets/components/DesignOption.jsx":
/*!******************************************************!*\
  !*** ./src/block-assets/components/DesignOption.jsx ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ DesignOption)
/* harmony export */ });
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _UseImage_jsx__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UseImage.jsx */ "./src/block-assets/components/UseImage.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__);



function DesignOption({
  props,
  value,
  DesignKey,
  options,
  help
}) {
  const {
    setAttributes
  } = props;
  const checkBoxEffect = event => {
    const button = event.target.parentNode;
    const dataValue = button.getAttribute('data-value');
    // Remove the "selected" class from all other buttons
    const buttons = document.querySelectorAll('.design-option-btn');
    buttons.forEach(btn => {
      btn = btn.parentNode;
      if (btn !== button) {
        btn.classList.remove('selected');
      }
    });

    // Toggle the "selected" class for the clicked button
    button.classList.toggle('selected');
    if (button.classList.contains('selected')) {
      setAttributes({
        [DesignKey]: dataValue
      });
    } else {
      setAttributes({
        [DesignKey]: ''
      });
    }
  };
  const getContrastingTextColor = bgColor => {
    // Convert the background color to RGB values
    let rgb = [];
    if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(bgColor)) {
      // Valid hex color value, convert to RGB
      rgb = bgColor.match(/\w{2}/g).map(hex => parseInt(hex, 16));
    } else if (/^rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)$/.test(bgColor)) {
      // Valid RGB color value
      rgb = bgColor.match(/\d+/g).map(Number);
    }
    const brightness = (rgb[0] * 299 + rgb[1] * 587 + rgb[2] * 114) / 1000; // Calculate brightness
    // Set the text color based on brightness threshold (you can adjust the threshold as needed)
    return brightness > 128 ? '#000000' : '#FFFFFF';
  };
  const Buttons = options.map((element, index) => {
    let selected = 'design-option-item';
    if (value === element.value) {
      selected = 'design-option-item selected';
    }
    let myStyle = {};
    if (element.display.startsWith('#')) {
      myStyle = {
        position: 'relative',
        '--bg_container_color': element.display,
        '--bg_container_circle_color': getContrastingTextColor(element.display),
        fontSize: '20px'
      };
    } else {
      const {
        image
      } = (0,_UseImage_jsx__WEBPACK_IMPORTED_MODULE_1__["default"])(element.display);
      if (image) {
        myStyle = {
          backgroundImage: 'url(' + image + ')'
        };
      }
    }
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.Fragment, {
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_wordpress_components__WEBPACK_IMPORTED_MODULE_0__.Tooltip, {
        text: element.label,
        position: 'bottom',
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
          className: selected,
          style: myStyle,
          "data-value": element.value,
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)("button", {
            onClick: event => {
              checkBoxEffect(event);
            },
            className: "design-option-btn",
            children: [element.display.startsWith('#') ? element.label : '', /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("svg", {
              xmlns: "http://www.w3.org/2000/svg",
              viewBox: "0 0 24 24",
              width: "24",
              height: "24",
              fill: "#fff",
              "aria-hidden": "true",
              focusable: "false",
              children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("path", {
                d: "M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"
              })
            })]
          }, index)
        })
      })
    });
  });
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsxs)(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.Fragment, {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "dc-sidebar-help-text",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("p", {
        className: "components-base-control__help",
        children: help
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", {
      className: "design-option",
      children: Buttons
    })]
  });
}

/***/ }),

/***/ "./src/block-assets/components/UseImage.jsx":
/*!**************************************************!*\
  !*** ./src/block-assets/components/UseImage.jsx ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);

const UseImage = fileName => {
  const [loading, setLoading] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(true);
  const [error, setError] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  const [image, setImage] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const fetchImage = async () => {
      try {
        const response = await Object(function webpackMissingModule() { var e = new Error("Cannot find module 'undefined'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()); // change relative path to suit your needs
        console.log(response);
        setImage(response);
      } catch (err) {
        setError(err);
      } finally {
        setLoading(false);
      }
    };
    fetchImage();
  }, [fileName]);
  return {
    loading,
    error,
    image
  };
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (UseImage);

/***/ }),

/***/ "./src/block-assets/icons/Icons.jsx":
/*!******************************************!*\
  !*** ./src/block-assets/icons/Icons.jsx ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__);

const icons = {};
icons.faq = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
  id: "fi_4291848",
  enableBackground: "new 0 0 511.999 511.999",
  height: "512",
  viewBox: "0 0 511.999 511.999",
  width: "512",
  xmlns: "http://www.w3.org/2000/svg",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "m466.999 70.499h-421.999c-24.813 0-45 20.187-45 45v241.006c0 24.814 20.187 45 45 45h173.5l25.5 33.999c6.002 8.003 18.011 7.986 24 0l25.5-33.999h173.499c24.814 0 45-20.186 45-45v-241.006c0-24.813-20.187-45-45-45zm15 286.006c0 8.271-6.729 15-15 15h-180.999c-4.722 0-9.167 2.223-12 6l-18 23.999-18-23.999c-2.833-3.777-7.278-6-12-6h-181c-8.271 0-15-6.729-15-15v-241.006c0-8.271 6.729-15 15-15h421.999c8.271 0 15 6.729 15 15z"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "m234.122 175.309c-2.491-6.621-8.918-11.305-16.423-11.305-7.165 0-13.558 4.284-16.275 10.926-.098.237 1.887-4.967-42.938 112.729-3.756 9.862 3.585 20.343 14.015 20.343 6.044 0 11.741-3.681 14.02-9.664l6.982-18.331h48.064l6.902 18.287c2.925 7.749 11.577 11.665 19.33 8.738 7.75-2.925 11.663-11.58 8.738-19.33zm-29.194 74.697 12.717-33.392 12.602 33.392z"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "m136.43 194.839c8.284 0 15-6.716 15-15s-6.716-15-15-15h-45.864c-8.284 0-15 6.716-15 15v113.158c0 8.284 6.716 15 15 15 8.283 0 15-6.716 15-15v-42.65h27.221c8.284 0 15-6.716 15-15s-6.716-15-15-15h-27.221v-25.508z"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "m424.723 274.61c7.126-11.166 11.275-24.408 11.275-38.608 0-39.699-32.299-71.997-71.997-71.997-39.7 0-71.996 32.298-71.996 71.997s32.296 71.996 71.996 71.996c14.666 0 28.315-4.418 39.706-11.978l7.124 7.125c5.859 5.858 15.355 5.858 21.213 0 5.858-5.857 5.858-15.355 0-21.213zm-102.718-38.609c0-23.157 18.84-41.997 41.996-41.997 30.039 0 50.476 30.854 38.614 58.498l-7.039-7.039c-5.857-5.857-15.355-5.857-21.213 0s-5.858 15.356 0 21.213l7.378 7.378c-27.634 12.934-59.736-7.284-59.736-38.053z"
    })]
  })
});
icons.container = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    fillRule: "evenodd",
    clipRule: "evenodd",
    d: "M64 15H0V47H64V15ZM4 43H60V19H4V43Z",
    fill: "black"
  })
});
icons.socialFeeds = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M4.66667 61.3333H11.3333C13.9067 61.3333 16 59.24 16 56.6667V26C16 23.4267 13.9067 21.3333 11.3333 21.3333H4.66667C2.09333 21.3333 0 23.4267 0 26V56.6667C0 59.24 2.09333 61.3333 4.66667 61.3333Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M34.0827 2C31.416 2 30.0827 3.33333 30.0827 10C30.0827 16.336 23.9467 21.4347 20 24.0613V57.096C24.2693 59.072 32.816 62 46.0827 62H50.3493C55.5493 62 59.976 58.2667 60.856 53.1467L63.8427 35.8133C64.9627 29.28 59.9493 23.3333 53.336 23.3333H40.7493C40.7493 23.3333 42.7493 19.3333 42.7493 12.6667C42.7493 4.66667 36.7493 2 34.0827 2Z",
    fill: "#6B5447"
  })]
});
icons.featuredPost = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "28",
  viewBox: "0 0 64 28",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_1329_27)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M60 4H40V24H60V4ZM36 0V28H64V0H36Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46.0714 12L40 20.2143V24H60V20.5714L56.0714 15.9286L52.8571 19.5L46.0714 12Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M58 9.5C58 10.8807 56.8807 12 55.5 12C54.1193 12 53 10.8807 53 9.5C53 8.11929 54.1193 7 55.5 7C56.8807 7 58 8.11929 58 9.5Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 12L28 16L-1.63189e-07 16L0 12L28 12Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 12L28 16L-1.63189e-07 16L0 12L28 12Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 12L28 16L-1.63189e-07 16L0 12L28 12Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 4L28 8L-1.63189e-07 8L0 4L28 4Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 4L28 8L-1.63189e-07 8L0 4L28 4Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 4L28 8L-1.63189e-07 8L0 4L28 4Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 20L14 24L-1.63189e-07 24L0 20L14 20Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 20L14 24L-1.63189e-07 24L0 20L14 20Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 20L14 24L-1.63189e-07 24L0 20L14 20Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("defs", {
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_1329_27",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "28",
        height: "28",
        fill: "white",
        transform: "translate(36)"
      })
    })
  })]
});
icons.featuredStays = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_1415_90)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M12.8186 29.0633H2.13643V40.7089H12.8186V29.0633ZM0 26.7342V43.038H14.955V26.7342H0Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M5.37927 33.7216L2.13647 38.5046V40.7089H12.8186V38.7125L10.7203 36.0091L9.00357 38.0886L5.37927 33.7216Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M11.7504 32.2658C11.7504 33.0698 11.1526 33.7215 10.4151 33.7215C9.67765 33.7215 9.07983 33.0698 9.07983 32.2658C9.07983 31.4619 9.67765 30.8101 10.4151 30.8101C11.1526 30.8101 11.7504 31.4619 11.7504 32.2658Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 57.0126L14.955 59.3417L1.01648e-05 59.3417L1.0252e-05 57.0126L14.955 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 57.0126L14.955 59.3417L1.01648e-05 59.3417L1.0252e-05 57.0126L14.955 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 57.0126L14.955 59.3417L1.01648e-05 59.3417L1.0252e-05 57.0126L14.955 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 52.3544L14.955 54.6835L1.01648e-05 54.6835L1.0252e-05 52.3544L14.955 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 52.3544L14.955 54.6835L1.01648e-05 54.6835L1.0252e-05 52.3544L14.955 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 52.3544L14.955 54.6835L1.01648e-05 54.6835L1.0252e-05 52.3544L14.955 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 47.6962L14.955 50.0253L1.01648e-05 50.0253L1.0252e-05 47.6962L14.955 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 47.6962L14.955 50.0253L1.01648e-05 50.0253L1.0252e-05 47.6962L14.955 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.955 47.6962L14.955 50.0253L1.01648e-05 50.0253L1.0252e-05 47.6962L14.955 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M7.47748 61.6709L7.47748 64L-2.54787e-05 64L-2.53916e-05 61.6709L7.47748 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M7.47748 61.6709L7.47748 64L-2.54787e-05 64L-2.53916e-05 61.6709L7.47748 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M7.47748 61.6709L7.47748 64L-2.54787e-05 64L-2.53916e-05 61.6709L7.47748 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip1_1415_90)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M29.1669 29.0633H18.4848V40.7089H29.1669V29.0633ZM16.3483 26.7342V43.038H31.3033V26.7342H16.3483Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M21.7275 33.7216L18.4847 38.5046V40.7089H29.1669V38.7125L27.0686 36.0091L25.3518 38.0886L21.7275 33.7216Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M28.0987 32.2658C28.0987 33.0698 27.5009 33.7215 26.7634 33.7215C26.026 33.7215 25.4282 33.0698 25.4282 32.2658C25.4282 31.4619 26.026 30.8101 26.7634 30.8101C27.5009 30.8101 28.0987 31.4619 28.0987 32.2658Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 57.0126L31.3033 59.3417L16.3483 59.3417L16.3483 57.0126L31.3033 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 57.0126L31.3033 59.3417L16.3483 59.3417L16.3483 57.0126L31.3033 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 57.0126L31.3033 59.3417L16.3483 59.3417L16.3483 57.0126L31.3033 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 52.3544L31.3033 54.6835L16.3483 54.6835L16.3483 52.3544L31.3033 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 52.3544L31.3033 54.6835L16.3483 54.6835L16.3483 52.3544L31.3033 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 52.3544L31.3033 54.6835L16.3483 54.6835L16.3483 52.3544L31.3033 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 47.6962L31.3033 50.0253L16.3483 50.0253L16.3483 47.6962L31.3033 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 47.6962L31.3033 50.0253L16.3483 50.0253L16.3483 47.6962L31.3033 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M31.3033 47.6962L31.3033 50.0253L16.3483 50.0253L16.3483 47.6962L31.3033 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M23.8258 61.6709L23.8258 64L16.3483 64L16.3483 61.6709L23.8258 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M23.8258 61.6709L23.8258 64L16.3483 64L16.3483 61.6709L23.8258 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M23.8258 61.6709L23.8258 64L16.3483 64L16.3483 61.6709L23.8258 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip2_1415_90)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M45.5152 29.0633H34.8331V40.7089H45.5152V29.0633ZM32.6967 26.7342V43.038H47.6517V26.7342H32.6967Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M38.0759 33.7216L34.8331 38.5046V40.7089H45.5152V38.7125L43.4169 36.0091L41.7002 38.0886L38.0759 33.7216Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M44.447 32.2658C44.447 33.0698 43.8492 33.7215 43.1118 33.7215C42.3743 33.7215 41.7765 33.0698 41.7765 32.2658C41.7765 31.4619 42.3743 30.8101 43.1118 30.8101C43.8492 30.8101 44.447 31.4619 44.447 32.2658Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 57.0126L47.6517 59.3417L32.6967 59.3417L32.6967 57.0126L47.6517 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 57.0126L47.6517 59.3417L32.6967 59.3417L32.6967 57.0126L47.6517 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 57.0126L47.6517 59.3417L32.6967 59.3417L32.6967 57.0126L47.6517 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 52.3544L47.6517 54.6835L32.6967 54.6835L32.6967 52.3544L47.6517 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 52.3544L47.6517 54.6835L32.6967 54.6835L32.6967 52.3544L47.6517 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 52.3544L47.6517 54.6835L32.6967 54.6835L32.6967 52.3544L47.6517 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 47.6962L47.6517 50.0253L32.6967 50.0253L32.6967 47.6962L47.6517 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 47.6962L47.6517 50.0253L32.6967 50.0253L32.6967 47.6962L47.6517 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M47.6517 47.6962L47.6517 50.0253L32.6967 50.0253L32.6967 47.6962L47.6517 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M40.1742 61.6709L40.1742 64L32.6967 64L32.6967 61.6709L40.1742 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M40.1742 61.6709L40.1742 64L32.6967 64L32.6967 61.6709L40.1742 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M40.1742 61.6709L40.1742 64L32.6967 64L32.6967 61.6709L40.1742 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip3_1415_90)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M61.8636 29.0633H51.1814V40.7089H61.8636V29.0633ZM49.045 26.7342V43.038H64V26.7342H49.045Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M54.4242 33.7216L51.1814 38.5046V40.7089H61.8635V38.7125L59.7653 36.0091L58.0485 38.0886L54.4242 33.7216Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M60.7954 32.2658C60.7954 33.0698 60.1975 33.7215 59.4601 33.7215C58.7226 33.7215 58.1248 33.0698 58.1248 32.2658C58.1248 31.4619 58.7226 30.8101 59.4601 30.8101C60.1975 30.8101 60.7954 31.4619 60.7954 32.2658Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 57.0126L64 59.3417L49.045 59.3417L49.045 57.0126L64 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 57.0126L64 59.3417L49.045 59.3417L49.045 57.0126L64 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 57.0126L64 59.3417L49.045 59.3417L49.045 57.0126L64 57.0126Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 52.3544L64 54.6835L49.045 54.6835L49.045 52.3544L64 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 52.3544L64 54.6835L49.045 54.6835L49.045 52.3544L64 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 52.3544L64 54.6835L49.045 54.6835L49.045 52.3544L64 52.3544Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 47.6962L64 50.0253L49.045 50.0253L49.045 47.6962L64 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 47.6962L64 50.0253L49.045 50.0253L49.045 47.6962L64 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 47.6962L64 50.0253L49.045 50.0253L49.045 47.6962L64 47.6962Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M56.5225 61.6709L56.5225 64L49.045 64L49.045 61.6709L56.5225 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M56.5225 61.6709L56.5225 64L49.045 64L49.045 61.6709L56.5225 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M56.5225 61.6709L56.5225 64L49.045 64L49.045 61.6709L56.5225 61.6709Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip4_1415_90)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M60 3.24051H40V19.443H60V3.24051ZM36 0V22.6835H64V0H36Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46.0714 9.7215L40 16.3761V19.443H60V16.6654L56.0714 12.9041L52.8571 15.7974L46.0714 9.7215Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M58 7.69621C58 8.81477 56.8807 9.72153 55.5 9.72153C54.1193 9.72153 53 8.81477 53 7.69621C53 6.57766 54.1193 5.6709 55.5 5.6709C56.8807 5.6709 58 6.57766 58 7.69621Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 9.7215L28 12.962L-1.63189e-07 12.962L0 9.7215L28 9.7215Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 9.7215L28 12.962L-1.63189e-07 12.962L0 9.7215L28 9.7215Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 9.7215L28 12.962L-1.63189e-07 12.962L0 9.7215L28 9.7215Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 3.24048L28 6.48099L-1.63189e-07 6.48099L0 3.24048L28 3.24048Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 3.24048L28 6.48099L-1.63189e-07 6.48099L0 3.24048L28 3.24048Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 3.24048L28 6.48099L-1.63189e-07 6.48099L0 3.24048L28 3.24048Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 16.2025L14 19.443L-1.63189e-07 19.443L0 16.2025L14 16.2025Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 16.2025L14 19.443L-1.63189e-07 19.443L0 16.2025L14 16.2025Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 16.2025L14 19.443L-1.63189e-07 19.443L0 16.2025L14 16.2025Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("defs", {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_1415_90",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "14.955",
        height: "16.3038",
        fill: "white",
        transform: "translate(0 26.7342)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip1_1415_90",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "14.955",
        height: "16.3038",
        fill: "white",
        transform: "translate(16.3483 26.7342)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip2_1415_90",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "14.955",
        height: "16.3038",
        fill: "white",
        transform: "translate(32.6967 26.7342)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip3_1415_90",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "14.955",
        height: "16.3038",
        fill: "white",
        transform: "translate(49.045 26.7342)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip4_1415_90",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "28",
        height: "22.6835",
        fill: "white",
        transform: "translate(36)"
      })
    })]
  })]
});
icons.heroHome = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14.2222 0H64V46.9333H56.8889V55.4667H49.7778V64H0V17.0667H7.11111V8.53333H14.2222V0ZM60.4444 42.6667V4.26667H17.7778V42.6667H60.4444ZM49.7778 25.6C45.8667 25.6 42.6667 21.76 42.6667 17.0667C42.6667 12.3733 45.8667 8.53333 49.7778 8.53333C53.6889 8.53333 56.8889 12.3733 56.8889 17.0667C56.8889 21.76 53.6889 25.6 49.7778 25.6ZM53.3333 51.2V46.9333H14.2222V12.8H10.6667V51.2H53.3333ZM21.3333 12.8L56.8889 38.4H21.3333V12.8ZM46.2222 59.7333V55.4667H7.11111V21.3333H3.55556V59.7333H46.2222Z",
    fill: "#6B5447"
  })
});
icons.elevateTeaser = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_1415_47)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      clipPath: "url(#clip1_1415_47)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        fillRule: "evenodd",
        clipRule: "evenodd",
        d: "M18.8571 9.14286H3.14286V24.8571H18.8571V9.14286ZM0 6V28H22V6H0Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M7.91323 15.4286L3.14282 21.8827V24.8572H18.8571V22.1633L15.7704 18.5153L13.2449 21.3214L7.91323 15.4286Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M17.2857 13.4643C17.2857 14.5491 16.4063 15.4286 15.3215 15.4286C14.2366 15.4286 13.3572 14.5491 13.3572 13.4643C13.3572 12.3794 14.2366 11.5 15.3215 11.5C16.4063 11.5 17.2857 12.3794 17.2857 13.4643Z",
        fill: "#6B5447"
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 46.8571L22 50L3.48617e-07 50L4.76837e-07 46.8571L22 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 46.8571L22 50L3.48617e-07 50L4.76837e-07 46.8571L22 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 46.8571L22 50L3.48617e-07 50L4.76837e-07 46.8571L22 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 40.5714L22 43.7143L3.48617e-07 43.7143L4.76837e-07 40.5714L22 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 40.5714L22 43.7143L3.48617e-07 43.7143L4.76837e-07 40.5714L22 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 40.5714L22 43.7143L3.48617e-07 43.7143L4.76837e-07 40.5714L22 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 34.2857L22 37.4286L3.48617e-07 37.4286L4.76837e-07 34.2857L22 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 34.2857L22 37.4286L3.48617e-07 37.4286L4.76837e-07 34.2857L22 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 34.2857L22 37.4286L3.48617e-07 37.4286L4.76837e-07 34.2857L22 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M11 53.1429L11 56.2857L1.10198e-07 56.2857L2.38419e-07 53.1429L11 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M11 53.1429L11 56.2857L1.10198e-07 56.2857L2.38419e-07 53.1429L11 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M11 53.1429L11 56.2857L1.10198e-07 56.2857L2.38419e-07 53.1429L11 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      clipPath: "url(#clip2_1415_47)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        fillRule: "evenodd",
        clipRule: "evenodd",
        d: "M42.8571 9.14286H27.1429V24.8571H42.8571V9.14286ZM24 6V28H46V6H24Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M31.9132 15.4286L27.1428 21.8827V24.8572H42.8571V22.1633L39.7704 18.5153L37.2449 21.3214L31.9132 15.4286Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M41.2857 13.4643C41.2857 14.5491 40.4063 15.4286 39.3215 15.4286C38.2366 15.4286 37.3572 14.5491 37.3572 13.4643C37.3572 12.3794 38.2366 11.5 39.3215 11.5C40.4063 11.5 41.2857 12.3794 41.2857 13.4643Z",
        fill: "#6B5447"
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 46.8571L46 50L24 50L24 46.8571L46 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 46.8571L46 50L24 50L24 46.8571L46 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 46.8571L46 50L24 50L24 46.8571L46 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 40.5714L46 43.7143L24 43.7143L24 40.5714L46 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 40.5714L46 43.7143L24 43.7143L24 40.5714L46 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 40.5714L46 43.7143L24 43.7143L24 40.5714L46 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 34.2857L46 37.4286L24 37.4286L24 34.2857L46 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 34.2857L46 37.4286L24 37.4286L24 34.2857L46 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 34.2857L46 37.4286L24 37.4286L24 34.2857L46 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M35 53.1429L35 56.2857L24 56.2857L24 53.1429L35 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M35 53.1429L35 56.2857L24 56.2857L24 53.1429L35 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M35 53.1429L35 56.2857L24 56.2857L24 53.1429L35 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      clipPath: "url(#clip3_1415_47)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        fillRule: "evenodd",
        clipRule: "evenodd",
        d: "M66.8571 9.14286H51.1429V24.8571H66.8571V9.14286ZM48 6V28H70V6H48Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M55.9132 15.4286L51.1428 21.8827V24.8572H66.8571V22.1633L63.7704 18.5153L61.2449 21.3214L55.9132 15.4286Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M65.2857 13.4643C65.2857 14.5491 64.4063 15.4286 63.3215 15.4286C62.2366 15.4286 61.3572 14.5491 61.3572 13.4643C61.3572 12.3794 62.2366 11.5 63.3215 11.5C64.4063 11.5 65.2857 12.3794 65.2857 13.4643Z",
        fill: "#6B5447"
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 46.8571L70 50L48 50L48 46.8571L70 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 46.8571L70 50L48 50L48 46.8571L70 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 46.8571L70 50L48 50L48 46.8571L70 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 40.5714L70 43.7143L48 43.7143L48 40.5714L70 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 40.5714L70 43.7143L48 43.7143L48 40.5714L70 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 40.5714L70 43.7143L48 43.7143L48 40.5714L70 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 34.2857L70 37.4286L48 37.4286L48 34.2857L70 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 34.2857L70 37.4286L48 37.4286L48 34.2857L70 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 34.2857L70 37.4286L48 37.4286L48 34.2857L70 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M59 53.1429L59 56.2857L48 56.2857L48 53.1429L59 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M59 53.1429L59 56.2857L48 56.2857L48 53.1429L59 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M59 53.1429L59 56.2857L48 56.2857L48 53.1429L59 53.1429Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("defs", {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "64",
        height: "64",
        fill: "white"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip1_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "22",
        height: "22",
        fill: "white",
        transform: "translate(0 6)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip2_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "22",
        height: "22",
        fill: "white",
        transform: "translate(24 6)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip3_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "22",
        height: "22",
        fill: "white",
        transform: "translate(48 6)"
      })
    })]
  })]
});
icons.perksTeaser = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_1415_47)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      clipPath: "url(#clip1_1415_47)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        fillRule: "evenodd",
        clipRule: "evenodd",
        d: "M18.8571 9.14286H3.14286V24.8571H18.8571V9.14286ZM0 6V28H22V6H0Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M7.91323 15.4286L3.14282 21.8827V24.8572H18.8571V22.1633L15.7704 18.5153L13.2449 21.3214L7.91323 15.4286Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M17.2857 13.4643C17.2857 14.5491 16.4063 15.4286 15.3215 15.4286C14.2366 15.4286 13.3572 14.5491 13.3572 13.4643C13.3572 12.3794 14.2366 11.5 15.3215 11.5C16.4063 11.5 17.2857 12.3794 17.2857 13.4643Z",
        fill: "#6B5447"
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 46.8571L22 50L3.48617e-07 50L4.76837e-07 46.8571L22 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 46.8571L22 50L3.48617e-07 50L4.76837e-07 46.8571L22 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 46.8571L22 50L3.48617e-07 50L4.76837e-07 46.8571L22 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 40.5714L22 43.7143L3.48617e-07 43.7143L4.76837e-07 40.5714L22 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 40.5714L22 43.7143L3.48617e-07 43.7143L4.76837e-07 40.5714L22 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 40.5714L22 43.7143L3.48617e-07 43.7143L4.76837e-07 40.5714L22 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 34.2857L22 37.4286L3.48617e-07 37.4286L4.76837e-07 34.2857L22 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 34.2857L22 37.4286L3.48617e-07 37.4286L4.76837e-07 34.2857L22 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 34.2857L22 37.4286L3.48617e-07 37.4286L4.76837e-07 34.2857L22 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M11 53.1429L11 56.2857L1.10198e-07 56.2857L2.38419e-07 53.1429L11 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M11 53.1429L11 56.2857L1.10198e-07 56.2857L2.38419e-07 53.1429L11 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M11 53.1429L11 56.2857L1.10198e-07 56.2857L2.38419e-07 53.1429L11 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      clipPath: "url(#clip2_1415_47)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        fillRule: "evenodd",
        clipRule: "evenodd",
        d: "M42.8571 9.14286H27.1429V24.8571H42.8571V9.14286ZM24 6V28H46V6H24Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M31.9132 15.4286L27.1428 21.8827V24.8572H42.8571V22.1633L39.7704 18.5153L37.2449 21.3214L31.9132 15.4286Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M41.2857 13.4643C41.2857 14.5491 40.4063 15.4286 39.3215 15.4286C38.2366 15.4286 37.3572 14.5491 37.3572 13.4643C37.3572 12.3794 38.2366 11.5 39.3215 11.5C40.4063 11.5 41.2857 12.3794 41.2857 13.4643Z",
        fill: "#6B5447"
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 46.8571L46 50L24 50L24 46.8571L46 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 46.8571L46 50L24 50L24 46.8571L46 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 46.8571L46 50L24 50L24 46.8571L46 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 40.5714L46 43.7143L24 43.7143L24 40.5714L46 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 40.5714L46 43.7143L24 43.7143L24 40.5714L46 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 40.5714L46 43.7143L24 43.7143L24 40.5714L46 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 34.2857L46 37.4286L24 37.4286L24 34.2857L46 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 34.2857L46 37.4286L24 37.4286L24 34.2857L46 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46 34.2857L46 37.4286L24 37.4286L24 34.2857L46 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M35 53.1429L35 56.2857L24 56.2857L24 53.1429L35 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M35 53.1429L35 56.2857L24 56.2857L24 53.1429L35 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M35 53.1429L35 56.2857L24 56.2857L24 53.1429L35 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
      clipPath: "url(#clip3_1415_47)",
      children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        fillRule: "evenodd",
        clipRule: "evenodd",
        d: "M66.8571 9.14286H51.1429V24.8571H66.8571V9.14286ZM48 6V28H70V6H48Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M55.9132 15.4286L51.1428 21.8827V24.8572H66.8571V22.1633L63.7704 18.5153L61.2449 21.3214L55.9132 15.4286Z",
        fill: "#6B5447"
      }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
        d: "M65.2857 13.4643C65.2857 14.5491 64.4063 15.4286 63.3215 15.4286C62.2366 15.4286 61.3572 14.5491 61.3572 13.4643C61.3572 12.3794 62.2366 11.5 63.3215 11.5C64.4063 11.5 65.2857 12.3794 65.2857 13.4643Z",
        fill: "#6B5447"
      })]
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 46.8571L70 50L48 50L48 46.8571L70 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 46.8571L70 50L48 50L48 46.8571L70 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 46.8571L70 50L48 50L48 46.8571L70 46.8571Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 40.5714L70 43.7143L48 43.7143L48 40.5714L70 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 40.5714L70 43.7143L48 43.7143L48 40.5714L70 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 40.5714L70 43.7143L48 43.7143L48 40.5714L70 40.5714Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 34.2857L70 37.4286L48 37.4286L48 34.2857L70 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 34.2857L70 37.4286L48 37.4286L48 34.2857L70 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M70 34.2857L70 37.4286L48 37.4286L48 34.2857L70 34.2857Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M59 53.1429L59 56.2857L48 56.2857L48 53.1429L59 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M59 53.1429L59 56.2857L48 56.2857L48 53.1429L59 53.1429Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M59 53.1429L59 56.2857L48 56.2857L48 53.1429L59 53.1429Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("defs", {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "64",
        height: "64",
        fill: "white"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip1_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "22",
        height: "22",
        fill: "white",
        transform: "translate(0 6)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip2_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "22",
        height: "22",
        fill: "white",
        transform: "translate(24 6)"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip3_1415_47",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "22",
        height: "22",
        fill: "white",
        transform: "translate(48 6)"
      })
    })]
  })]
});
icons.categoryTeaser = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M26 26H38V38H26V26Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M52 26H64V38H52V26Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M0 26H12V38H0V26Z",
    fill: "#6B5447"
  })]
});
icons.postTeaser = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "69",
  viewBox: "0 0 64 69",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
    x: "0.5",
    y: "5.5",
    width: "63",
    height: "63",
    stroke: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 51L41 69L23 69L23 51L41 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 51L41 69L23 69L23 51L41 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 51L41 69L23 69L23 51L41 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 28L41 46L23 46L23 28L41 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 28L41 46L23 46L23 28L41 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 28L41 46L23 46L23 28L41 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 51L18 69L-8.39259e-07 69L0 51L18 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 51L18 69L-8.39259e-07 69L0 51L18 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 51L18 69L-8.39259e-07 69L0 51L18 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 28L64 46L46 46L46 28L64 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 28L64 46L46 46L46 28L64 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 28L64 46L46 46L46 28L64 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 28L64 46L63 46L63 28L64 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 28L64 46L63 46L63 28L64 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 28L64 46L63 46L63 28L64 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L46 69L46 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L46 69L46 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L46 69L46 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L63 69L63 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L63 69L63 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L63 69L63 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L-8.39259e-07 46L0 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L-8.39259e-07 46L0 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L-8.39259e-07 46L0 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L46 69L46 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L46 69L46 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 51L64 69L46 69L46 51L64 51Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L-8.39259e-07 46L0 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L-8.39259e-07 46L0 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L-8.39259e-07 46L0 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L17 46L17 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L17 46L17 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 28L18 46L17 46L17 28L18 28Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 5L41 23L23 23L23 5L41 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 5L41 23L23 23L23 5L41 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41 5L41 23L23 23L23 5L41 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 5L18 23L-8.39259e-07 23L0 5L18 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 5L18 23L-8.39259e-07 23L0 5L18 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18 5L18 23L-8.39259e-07 23L0 5L18 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L46 23L46 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L46 23L46 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L46 23L46 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L63 23L63 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L63 23L63 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L63 23L63 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L46 23L46 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L46 23L46 5L64 5Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 5L64 23L46 23L46 5L64 5Z",
    fill: "#6B5447"
  })]
});
icons.videoTeaser = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
    clipPath: "url(#clip0_738_1269)",
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M64 64C66.0669 64 64 66.8187 64 64H7.33137e-06C7.33137e-06 66.8187 -2.06695 64 7.33137e-06 64V1.00136e-05C-2.06695 1.00136e-05 7.33137e-06 -2.81869 7.33137e-06 1.00136e-05H64C64 -2.81869 66.0669 1.00136e-05 64 1.00136e-05V64ZM44.4691 29.1429L22.6513 16.9172C21.9525 16.5264 21.1568 16.6349 20.5292 17.2083C19.9018 17.7817 19.525 18.7441 19.525 19.7741V44.2255C19.525 45.2555 19.9015 46.2179 20.5292 46.7913C20.9077 47.1371 21.3476 47.314 21.7903 47.314C22.0814 47.314 22.3739 47.2375 22.6513 47.0824L44.4691 34.8567C45.3186 34.3807 45.8731 33.2521 45.8731 31.9998C45.8731 30.7475 45.3186 29.6193 44.4691 29.1429ZM24.0554 39.6173V24.3823L37.649 31.9998L24.0554 39.6173Z",
      fill: "#6B5447"
    })
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("defs", {
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_738_1269",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "64",
        height: "64",
        fill: "white"
      })
    })
  })]
});
icons.serviceTeaser = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M26 26H38V38H26V26Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M52 26H64V38H52V26Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M0 26H12V38H0V26Z",
    fill: "#6B5447"
  })]
});
icons.midPageCta = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "28",
  viewBox: "0 0 64 28",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_1329_27)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M60 4H40V24H60V4ZM36 0V28H64V0H36Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46.0714 12L40 20.2143V24H60V20.5714L56.0714 15.9286L52.8571 19.5L46.0714 12Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M58 9.5C58 10.8807 56.8807 12 55.5 12C54.1193 12 53 10.8807 53 9.5C53 8.11929 54.1193 7 55.5 7C56.8807 7 58 8.11929 58 9.5Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 12L28 16L-1.63189e-07 16L0 12L28 12Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 12L28 16L-1.63189e-07 16L0 12L28 12Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 12L28 16L-1.63189e-07 16L0 12L28 12Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 4L28 8L-1.63189e-07 8L0 4L28 4Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 4L28 8L-1.63189e-07 8L0 4L28 4Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 4L28 8L-1.63189e-07 8L0 4L28 4Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 20L14 24L-1.63189e-07 24L0 20L14 20Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 20L14 24L-1.63189e-07 24L0 20L14 20Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 20L14 24L-1.63189e-07 24L0 20L14 20Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("defs", {
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_1329_27",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "28",
        height: "28",
        fill: "white",
        transform: "translate(36)"
      })
    })
  })]
});
icons.newsLetter = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M61.64 17L61.64 27.1363L3 27.1363L3 17L61.64 17Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M61.64 17L61.64 27.1363L3 27.1363L3 17L61.64 17Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M61.64 17L61.64 27.1363L3 27.1363L3 17L61.64 17Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 42.475L49.1983 46.9207L15.4417 46.9207L15.4417 42.475L49.1983 42.475Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 42.475L49.1983 46.9207L15.4417 46.9207L15.4417 42.475L49.1983 42.475Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 42.475L49.1983 46.9207L15.4417 46.9207L15.4417 42.475L49.1983 42.475Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 34.6949L49.1983 39.1407L15.4417 39.1407L15.4417 34.6949L49.1983 34.6949Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 34.6949L49.1983 39.1407L15.4417 39.1407L15.4417 34.6949L49.1983 34.6949Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 34.6949L49.1983 39.1407L15.4417 39.1407L15.4417 34.6949L49.1983 34.6949Z",
    fill: "#6b5447"
  })]
});
icons.sectionHead = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M61.64 17L61.64 27.1363L3 27.1363L3 17L61.64 17Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M61.64 17L61.64 27.1363L3 27.1363L3 17L61.64 17Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M61.64 17L61.64 27.1363L3 27.1363L3 17L61.64 17Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 42.475L49.1983 46.9207L15.4417 46.9207L15.4417 42.475L49.1983 42.475Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 42.475L49.1983 46.9207L15.4417 46.9207L15.4417 42.475L49.1983 42.475Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 42.475L49.1983 46.9207L15.4417 46.9207L15.4417 42.475L49.1983 42.475Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 34.6949L49.1983 39.1407L15.4417 39.1407L15.4417 34.6949L49.1983 34.6949Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 34.6949L49.1983 39.1407L15.4417 39.1407L15.4417 34.6949L49.1983 34.6949Z",
    fill: "#6b5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M49.1983 34.6949L49.1983 39.1407L15.4417 39.1407L15.4417 34.6949L49.1983 34.6949Z",
    fill: "#6b5447"
  })]
});
icons.themeImage = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_738_742)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M60 4H4V60H60V4ZM0 0V64H64V0H0Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M21 18L4 41V60H60V42L49 29L40 39L21 18Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M50 16C50 19.866 46.866 23 43 23C39.134 23 36 19.866 36 16C36 12.134 39.134 9 43 9C46.866 9 50 12.134 50 16Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("defs", {
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_738_742",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "64",
        height: "64",
        fill: "white"
      })
    })
  })]
});
icons.textImageColumn = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_738_1087)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M24 4H4V24H24V4ZM0 0V28H28V0H0Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M10.0714 12L4 20.2143V24H24V20.5714L20.0714 15.9286L16.8571 19.5L10.0714 12Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 9.5C22 10.8807 20.8807 12 19.5 12C18.1193 12 17 10.8807 17 9.5C17 8.11929 18.1193 7 19.5 7C20.8807 7 22 8.11929 22 9.5Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 52L28 56L-1.63189e-07 56L0 52L28 52Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 52L28 56L-1.63189e-07 56L0 52L28 52Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 52L28 56L-1.63189e-07 56L0 52L28 52Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 44L28 48L-1.63189e-07 48L0 44L28 44Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 44L28 48L-1.63189e-07 48L0 44L28 44Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 44L28 48L-1.63189e-07 48L0 44L28 44Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 36L28 40L-1.63189e-07 40L0 36L28 36Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 36L28 40L-1.63189e-07 40L0 36L28 36Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M28 36L28 40L-1.63189e-07 40L0 36L28 36Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 60L14 64L-1.63189e-07 64L0 60L14 60Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 60L14 64L-1.63189e-07 64L0 60L14 60Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M14 60L14 64L-1.63189e-07 64L0 60L14 60Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip1_738_1087)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M60 4H40V24H60V4ZM36 0V28H64V0H36Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46.0714 12L40 20.2143V24H60V20.5714L56.0714 15.9286L52.8571 19.5L46.0714 12Z",
      fill: "#6B5447"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M56 9.5C56 10.8807 54.8807 12 53.5 12C52.1193 12 51 10.8807 51 9.5C51 8.11929 52.1193 7 53.5 7C54.8807 7 56 8.11929 56 9.5Z",
      fill: "#6B5447"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 52L64 56L36 56L36 52L64 52Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 52L64 56L36 56L36 52L64 52Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 52L64 56L36 56L36 52L64 52Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 44L64 48L36 48L36 44L64 44Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 44L64 48L36 48L36 44L64 44Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 44L64 48L36 48L36 44L64 44Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 36L64 40L36 40L36 36L64 36Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 36L64 40L36 40L36 36L64 36Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 36L64 40L36 40L36 36L64 36Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M50 60L50 64L36 64L36 60L50 60Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M50 60L50 64L36 64L36 60L50 60Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M50 60L50 64L36 64L36 60L50 60Z",
    fill: "#6B5447"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("defs", {
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip0_738_1087",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "28",
        height: "28",
        fill: "white"
      })
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("clipPath", {
      id: "clip1_738_1087",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
        width: "28",
        height: "28",
        fill: "white",
        transform: "translate(36)"
      })
    })]
  })]
});
icons.socials = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("svg", {
  id: "fi_4187236",
  enableBackground: "new 0 0 517.395 517.395",
  height: "512",
  viewBox: "0 0 517.395 517.395",
  width: "512",
  xmlns: "http://www.w3.org/2000/svg",
  children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("g", {
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "m397.204 156.407h12.079v47.674c0 4.143 3.357 7.5 7.5 7.5s7.5-3.357 7.5-7.5v-47.674h12.078c4.143 0 7.5-3.357 7.5-7.5s-3.357-7.5-7.5-7.5h-12.078v-21.79c0-4.188 3.406-7.594 7.594-7.594h17.772c4.143 0 7.5-3.357 7.5-7.5s-3.357-7.5-7.5-7.5h-17.772c-12.458 0-22.594 10.136-22.594 22.594v21.79h-12.079c-4.143 0-7.5 3.357-7.5 7.5s3.358 7.5 7.5 7.5zm-135.809-112.95c4.142 0 7.5-3.358 7.5-7.5s-3.358-7.5-7.5-7.5-7.5 3.358-7.5 7.5 3.357 7.5 7.5 7.5zm-162.033 130.314c12.406 0 22.5-10.094 22.5-22.5s-10.094-22.5-22.5-22.5-22.5 10.094-22.5 22.5 10.094 22.5 22.5 22.5zm0-30c4.136 0 7.5 3.364 7.5 7.5s-3.364 7.5-7.5 7.5-7.5-3.364-7.5-7.5 3.365-7.5 7.5-7.5zm30 60c12.406 0 22.5-10.094 22.5-22.5v-60c0-12.406-10.094-22.5-22.5-22.5h-60c-12.406 0-22.5 10.094-22.5 22.5v60c0 12.406 10.094 22.5 22.5 22.5zm-67.5-22.5v-60c0-4.136 3.364-7.5 7.5-7.5h60c4.136 0 7.5 3.364 7.5 7.5v60c0 4.136-3.364 7.5-7.5 7.5h-60c-4.135 0-7.5-3.365-7.5-7.5zm82.954 133.598c-3.641-2.739-8.46-3.199-12.579-1.204-1.01.489-2.071.859-3.154 1.102-1.322.295-2.668.405-4.024.322-4.42-4.062-10.044-6.493-16.121-6.916-7.083-.501-13.947 1.802-19.311 6.466-4.948 4.304-8.09 10.218-8.923 16.626-8.727-1.407-16.722-5.824-22.618-12.646-2.419-2.801-6.167-4.053-9.772-3.272-3.587.776-6.458 3.448-7.493 6.97-2.523 8.571-7.848 34.828 14.316 52.334-4.23 1.29-9.054 1.193-11.521 5.477-2.224 3.862-1.035 8.411-.128 10.78 1.772 4.626 6.396 7.956 13.741 9.897 4.009 1.06 9.249 1.745 15.154 1.745 13.696-.001 30.955-3.688 44.632-14.936 13.631-11.211 19.205-26.495 21.453-38.092 3.499-3.988 6.271-8.489 8.254-13.411 1.24-3.08 2.153-6.304 2.716-9.585.765-4.472-1.005-8.939-4.622-11.657zm-18.729 25.959c-1.062 1.097-1.766 2.491-2.014 3.998-1.531 9.271-5.651 22.21-16.586 31.203-13.221 10.872-31.83 12.399-42.532 11.021 3.571-1.482 6.994-3.322 10.18-5.485 2.253-1.529 3.501-4.159 3.259-6.872-.242-2.712-1.936-5.08-4.425-6.187-18.675-8.301-21.617-22.431-20.457-33.514 9.708 7.864 21.929 12.097 34.665 11.739 4.065-.105 7.306-3.431 7.306-7.497v-4.265c0-.033 0-.067-.001-.101-.046-3.42 1.408-6.669 3.989-8.913 2.339-2.035 5.33-3.033 8.424-2.821 3.093.216 5.917 1.623 7.951 3.962 1.091 1.254 2.565 2.109 4.195 2.434 4.439.883 8.932.769 13.27-.356-1.531 4.36-3.987 8.313-7.224 11.654zm297.34-79.462c-.178 0-.354.007-.532.008v-16.142c.178.001.354.007.532.007 51.814 0 93.968-42.153 93.968-93.968 0-52.025-42.494-94.256-94.5-93.961v-7.112c0-26.191-21.309-47.5-47.5-47.5h-228c-26.191 0-47.5 21.309-47.5 47.5v7.112c-18.374-.107-36.202 5.094-51.558 15.041-3.477 2.252-4.47 6.896-2.218 10.372 2.253 3.477 6.896 4.47 10.372 2.218 12.763-8.268 27.588-12.638 42.871-12.638 43.543 0 78.968 35.425 78.968 78.968s-35.425 78.968-78.968 78.968-78.968-35.425-78.968-78.968c0-15.786 4.641-31.02 13.419-44.054 2.313-3.436 1.404-8.096-2.031-10.41-3.436-2.312-8.096-1.404-10.41 2.031-10.452 15.52-15.978 33.65-15.978 52.433 0 51.814 42.153 93.968 93.968 93.968.178 0 .354-.006.532-.007v16.327c-1.961-.123-3.936-.192-5.927-.192-51.814-.001-93.967 42.153-93.967 93.967s42.153 93.969 93.968 93.969c1.991 0 3.966-.07 5.927-.192v18.087c0 26.191 21.309 47.5 47.5 47.5h228c26.191 0 47.5-21.309 47.5-47.5v-17.901c.178.001.354.007.532.007 51.814 0 93.968-42.154 93.968-93.969s-42.154-93.969-93.968-93.969zm0-189.063c43.543 0 78.968 35.425 78.968 78.968s-35.425 78.968-78.968 78.968-78.968-35.425-78.968-78.968 35.425-78.968 78.968-78.968zm-15.532 171.644v18.693c-5.128.846-10.141 2.111-15 3.77v-26.268c4.834 1.666 9.847 2.945 15 3.805zm-278-188.75v7.201c-4.835-1.666-9.847-2.944-15-3.805v-8.396c0-17.921 14.579-32.5 32.5-32.5h228c17.921 0 32.5 14.579 32.5 32.5v8.396c-5.153.861-10.165 2.139-15 3.805v-7.201c0-12.406-10.094-22.5-22.5-22.5h-27.55c-8.547 0-15.5 6.953-15.5 15.5 0 3.319-2.701 6.02-6.021 6.02h-119.859c-3.319 0-6.021-2.7-6.021-6.02 0-4.145-1.611-8.038-4.557-10.983-2.935-2.912-6.82-4.517-10.943-4.517h-27.55c-12.406 0-22.499 10.094-22.499 22.5zm-15 188.75c5.153-.861 10.165-2.139 15-3.805v28.368c-4.813-1.999-9.825-3.611-15-4.792zm-20.927 190.356c-43.543 0-78.968-35.425-78.968-78.969 0-43.543 35.425-78.968 78.968-78.968s78.968 35.425 78.968 78.968c0 46.025-36.331 78.969-78.968 78.969zm313.927 32.894c0 17.921-14.579 32.5-32.5 32.5h-228c-17.921 0-32.5-14.579-32.5-32.5v-20.246c5.175-1.181 10.187-2.794 15-4.793v20.039c0 12.406 10.094 22.5 22.5 22.5h218c12.406 0 22.5-10.094 22.5-22.5v-17.99c4.835 1.666 9.847 2.944 15 3.805zm15.532-32.894c-43.543 0-78.968-35.425-78.968-78.969 0-6.767.855-13.484 2.544-19.965 1.044-4.009-1.359-8.104-5.367-9.148-4.009-1.046-8.104 1.359-9.148 5.367-2.01 7.715-3.028 15.704-3.028 23.746 0 35.305 19.574 66.119 48.436 82.175v24.688c0 4.136-3.364 7.5-7.5 7.5h-218c-4.136 0-7.5-3.364-7.5-7.5v-27.927c25.88-16.755 43.041-45.878 43.041-78.936s-17.161-62.181-43.041-78.936v-42.954c28.862-16.056 48.436-46.87 48.436-82.174s-19.574-66.118-48.436-82.174v-13.899c0-4.136 3.364-7.5 7.5-7.5h27.55c.141 0 .268.055.356.144.129.128.144.265.144.356 0 11.59 9.43 21.02 21.021 21.02h119.859c11.591 0 21.021-9.43 21.021-21.02 0-.275.225-.5.5-.5h27.55c4.136 0 7.5 3.364 7.5 7.5v13.899c-28.862 16.056-48.436 46.87-48.436 82.174 0 35.326 19.598 66.156 48.489 82.204-.032.283-.053.57-.053.862v38.729c-11.06 6.132-20.911 14.542-28.927 24.939-2.529 3.28-1.92 7.989 1.36 10.519 3.282 2.53 7.989 1.922 10.519-1.36 15.089-19.58 37.575-30.797 62.58-30.797 43.543 0 78.968 35.425 78.968 78.968-.002 43.544-35.427 78.969-78.97 78.969zm-134.548-178.086c-3.541-2.147-8.153-1.021-10.304 2.519-1.39 2.289-3.349 4.25-5.598 5.705-2.167 1.402-4.989 1.442-7.193.104l-11.225-6.814c-2.238-1.359-3.462-3.968-3.209-6.613.25-2.61 1.102-5.175 2.463-7.416 2.15-3.541 1.023-8.153-2.517-10.304-3.539-2.15-8.153-1.024-10.304 2.517-2.528 4.163-4.11 8.926-4.577 13.802l-.014.151c-.792 8.258 3.279 16.376 10.371 20.685l11.225 6.814c3.452 2.097 7.375 3.142 11.296 3.142 4.134 0 8.266-1.162 11.838-3.477 4.139-2.682 7.708-6.295 10.266-10.51 2.15-3.543 1.022-8.155-2.518-10.305zm174.013 91.075-52.588-30.377c-6.802-4.004-15.666 1.201-15.607 9.01v60.755c-.07 7.791 8.859 12.99 15.606 9.01l52.588-30.376c6.8-3.854 6.775-14.186.001-18.022zm-53.196 31.426v-44.831l38.806 22.416zm-150.122-187.936c-34.855.914-63.84 28.876-65.986 63.658-.548 8.858.603 17.583 3.418 25.937l-7.986 22.719c-1.481 4.212-.439 8.789 2.717 11.946 3.156 3.156 7.734 4.197 11.945 2.717l15.839-5.567c12.427 9.742 27.965 14.847 43.755 14.419 36.303-.984 65.937-31.347 66.058-67.682.062-18.53-7.202-35.868-20.454-48.82-13.249-12.95-30.769-19.824-49.306-19.327zm3.295 120.834c-12.385.315-24.521-3.684-34.206-11.318-2.646-2.087-5.832-3.172-9.058-3.172-1.609 0-3.229.27-4.795.821l-9.221 3.241 5.627-16.007c1.072-3.049 1.095-6.395.062-9.421-2.236-6.56-3.151-13.422-2.721-20.396 1.673-27.095 24.254-48.876 51.408-49.589 14.457-.392 28.104 4.97 38.428 15.06 10.326 10.093 15.986 23.604 15.938 38.042-.093 28.314-23.18 51.972-51.462 52.739z"
    })
  })
});
icons.serviceteaser = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  width: "64",
  height: "45",
  viewBox: "0 0 64 45",
  fill: "none",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip0_1355_804)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M24 6.42857H4V38.5714H24V6.42857ZM0 0V45H28V0H0Z",
      fill: "black"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M10.0714 19.2857L4 32.4872V38.5714H24V33.0612L20.0714 25.5995L16.8571 31.3393L10.0714 19.2857Z",
      fill: "black"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M22 15.2679C22 17.4869 20.8807 19.2857 19.5 19.2857C18.1193 19.2857 17 17.4869 17 15.2679C17 13.0489 18.1193 11.25 19.5 11.25C20.8807 11.25 22 13.0489 22 15.2679Z",
      fill: "black"
    })]
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("g", {
    clipPath: "url(#clip1_1355_804)",
    children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      fillRule: "evenodd",
      clipRule: "evenodd",
      d: "M60 6.42857H40V38.5714H60V6.42857ZM36 0V45H64V0H36Z",
      fill: "black"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M46.0714 19.2857L40 32.4872V38.5714H60V33.0612L56.0714 25.5995L52.8571 31.3393L46.0714 19.2857Z",
      fill: "black"
    }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
      d: "M58 15.2679C58 17.4869 56.8807 19.2857 55.5 19.2857C54.1193 19.2857 53 17.4869 53 15.2679C53 13.0489 54.1193 11.25 55.5 11.25C56.8807 11.25 58 13.0489 58 15.2679Z",
      fill: "black"
    })]
  })]
});
icons.postgrid = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  xmlns: "http://www.w3.org/2000/svg",
  children: [" ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("rect", {
    width: "64",
    height: "64",
    transform: "matrix(1 0 0 -1 0 64)",
    fill: "white"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18.1272 23V38H-1.90735e-06V23L18.1272 23Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 23V38H45.8728V23L64 23Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41.4335 23V38H23.3064V23L41.4335 23Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18.1272 48.7283V63.7283H3.8147e-06V48.7283L18.1272 48.7283Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 48.7283V63.7283H45.8728V48.7283L64 48.7283Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M41.4335 48.7283V63.7283H23.3063V48.7283L41.4335 48.7283Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M16 0V14L0 14V0L16 0Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M64 0V14L48 14V0L64 0Z",
    fill: "#6B5447"
  }), " ", /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M40 0V14L24 14V0L40 0Z",
    fill: "#6B5447"
  }), " "]
});
icons.item = /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsxs)("svg", {
  xmlns: "http://www.w3.org/2000/svg",
  width: "64",
  height: "64",
  viewBox: "0 0 64 64",
  fill: "none",
  children: [/*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M9.33325 20C11.5424 20 13.3333 18.2091 13.3333 16C13.3333 13.7909 11.5424 12 9.33325 12C7.12411 12 5.33325 13.7909 5.33325 16C5.33325 18.2091 7.12411 20 9.33325 20Z",
    fill: "black"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M21.3334 13.3333C19.8607 13.3333 18.6667 14.5273 18.6667 16C18.6667 17.4728 19.8607 18.6667 21.3334 18.6667H56.0001C57.4729 18.6667 58.6667 17.4728 58.6667 16C58.6667 14.5273 57.4729 13.3333 56.0001 13.3333H21.3334Z",
    fill: "black"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M21.3334 29.3333C19.8607 29.3333 18.6667 30.5272 18.6667 32C18.6667 33.4728 19.8607 34.6667 21.3334 34.6667H56.0001C57.4729 34.6667 58.6667 33.4728 58.6667 32C58.6667 30.5272 57.4729 29.3333 56.0001 29.3333H21.3334Z",
    fill: "black"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M18.6667 48C18.6667 46.5272 19.8607 45.3333 21.3334 45.3333H56.0001C57.4729 45.3333 58.6667 46.5272 58.6667 48C58.6667 49.4728 57.4729 50.6667 56.0001 50.6667H21.3334C19.8607 50.6667 18.6667 49.4728 18.6667 48Z",
    fill: "black"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M13.3333 32C13.3333 34.2091 11.5424 36 9.33325 36C7.12411 36 5.33325 34.2091 5.33325 32C5.33325 29.7909 7.12411 28 9.33325 28C11.5424 28 13.3333 29.7909 13.3333 32Z",
    fill: "black"
  }), /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_0__.jsx)("path", {
    d: "M9.33325 52C11.5424 52 13.3333 50.2091 13.3333 48C13.3333 45.7909 11.5424 44 9.33325 44C7.12411 44 5.33325 45.7909 5.33325 48C5.33325 50.2091 7.12411 52 9.33325 52Z",
    fill: "black"
  })]
});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (icons);

/***/ }),

/***/ "./src/block-assets/preview-images/default-preview-image.webp":
/*!********************************************************************!*\
  !*** ./src/block-assets/preview-images/default-preview-image.webp ***!
  \********************************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

module.exports = __webpack_require__.p + "images/default-preview-image.0e34354f.webp";

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "react/jsx-runtime":
/*!**********************************!*\
  !*** external "ReactJSXRuntime" ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["ReactJSXRuntime"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./src/hero-home/block.json":
/*!**********************************!*\
  !*** ./src/hero-home/block.json ***!
  \**********************************/
/***/ ((module) => {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://raw.githubusercontent.com/WordPress/gutenberg/trunk/schemas/json/block.json","apiVersion":3,"name":"bivinsagencypack/hero-home","version":"1.0.0","title":"Hero Home","category":"theme-blocks","icon":"align-center","description":"","supports":{"html":false,"align":["wide","full"]},"attributes":{"preview":{"type":"boolean","default":false},"blocktext":{"type":"string","default":"default"}},"example":{"attributes":{"preview":true}},"textdomain":"bivins_td","editorScript":"file:./block-hero-home.js"}');

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		var scriptUrl;
/******/ 		if (__webpack_require__.g.importScripts) scriptUrl = __webpack_require__.g.location + "";
/******/ 		var document = __webpack_require__.g.document;
/******/ 		if (!scriptUrl && document) {
/******/ 			if (document.currentScript)
/******/ 				scriptUrl = document.currentScript.src;
/******/ 			if (!scriptUrl) {
/******/ 				var scripts = document.getElementsByTagName("script");
/******/ 				if(scripts.length) {
/******/ 					var i = scripts.length - 1;
/******/ 					while (i > -1 && (!scriptUrl || !/^http(s?):/.test(scriptUrl))) scriptUrl = scripts[i--].src;
/******/ 				}
/******/ 			}
/******/ 		}
/******/ 		// When supporting browsers where an automatic publicPath is not supported you must specify an output.publicPath manually via configuration
/******/ 		// or pass an empty string ("") and set the __webpack_public_path__ variable from your code to use your own logic.
/******/ 		if (!scriptUrl) throw new Error("Automatic publicPath is not supported in this browser");
/******/ 		scriptUrl = scriptUrl.replace(/#.*$/, "").replace(/\?.*$/, "").replace(/\/[^\/]+$/, "/");
/******/ 		__webpack_require__.p = scriptUrl + "../";
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
/*!*******************************************!*\
  !*** ./src/hero-home/block-hero-home.jsx ***!
  \*******************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./block.json */ "./src/hero-home/block.json");
/* harmony import */ var _block_assets_icons_Icons_jsx__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../block-assets/icons/Icons.jsx */ "./src/block-assets/icons/Icons.jsx");
/* harmony import */ var _block_assets_preview_images_default_preview_image_webp__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../block-assets/preview-images/default-preview-image.webp */ "./src/block-assets/preview-images/default-preview-image.webp");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _block_assets_components_ContainerBlock_jsx__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../block-assets/components/ContainerBlock.jsx */ "./src/block-assets/components/ContainerBlock.jsx");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react/jsx-runtime */ "react/jsx-runtime");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__);







_block_assets_components_ContainerBlock_jsx__WEBPACK_IMPORTED_MODULE_5__.customAttributes.bgWidth.default = 'ctn-full-width';
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_1__.name, {
  attributes: {
    ..._block_json__WEBPACK_IMPORTED_MODULE_1__.attributes,
    ..._block_assets_components_ContainerBlock_jsx__WEBPACK_IMPORTED_MODULE_5__.customAttributes
  },
  icon: _block_assets_icons_Icons_jsx__WEBPACK_IMPORTED_MODULE_2__["default"].themeStats,
  edit: Edit,
  save: Save
});
function Edit(props) {
  const {
    attributes,
    setAttributes
  } = props;
  const {
    preview,
    className
  } = attributes;
  const myCustomClassName = className ? className : undefined;
  const classes = [myCustomClassName].join(' ');
  const allowedBlocks = ['core/columns', 'core/column', 'core/image', 'core/heading', 'core/group', 'core/button'];
  const TEMPLATE = [['core/cover', {
    className: '',
    contentPosition: 'center left',
    dimRatio: 30,
    isUserOverlayColor: true,
    gradient: 'bottom-darker'
  }, [['core/group', {
    className: 'hero-content'
  }, [['core/paragraph', {
    className: 'kicker-text',
    content: '',
    placeholder: 'Kicker Text...'
  }], ['core/heading', {
    level: 1
  }], ['core/paragraph', {
    className: '',
    content: '',
    placeholder: 'Hero Content...'
  }], ['core/buttons', {}, [['core/button', {
    text: '',
    className: ''
  }]]]]]]]];
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__.useBlockProps)();
  const innerBlocksProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__.useInnerBlocksProps)(blockProps, {
    template: TEMPLATE,
    templateLock: false,
    // set 'all' if you want to lock structure
    allowedBlocks
  });

  // Block preview
  if (preview) {
    return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      className: "block-preview",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("img", {
        src: _block_assets_preview_images_default_preview_image_webp__WEBPACK_IMPORTED_MODULE_3__,
        alt: "Preview",
        style: {
          width: '100%',
          height: '100%',
          objectFit: 'cover'
        }
      })
    });
  }
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
    ...blockProps,
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_block_assets_components_ContainerBlock_jsx__WEBPACK_IMPORTED_MODULE_5__["default"], {
      props: props,
      className: classes,
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
        className: "hero-home hero-home-section",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
          className: "hero-inner",
          children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
            ...innerBlocksProps
          })
        })
      })
    })
  });
}
function Save(props) {
  const {
    attributes
  } = props;
  const {
    className
  } = attributes;
  const myCustomClassName = className ? className : '';
  const classes = [myCustomClassName].join(' ');
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_block_assets_components_ContainerBlock_jsx__WEBPACK_IMPORTED_MODULE_5__.ContainerBlockContent, {
    props: props,
    className: classes,
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
      className: "hero-home hero-home-section",
      children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)("div", {
        className: "hero-inner",
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_6__.jsx)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_4__.InnerBlocks.Content, {})
      })
    })
  });
}
/******/ })()
;
//# sourceMappingURL=block-hero-home.js.map