(window.webpackJsonp=window.webpackJsonp||[]).push([[8],{789:function(t,e,r){},798:function(t,e,r){"use strict";var n=r(789);r.n(n).a},807:function(t,e,r){"use strict";r.r(e);r(49),r(116);var n=r(51),o=r(71),c=r.n(o),l=r(223),d={layout:"simple",head:function(){return{title:"User register"}},data:function(){return{file:null,formError:{name:[],email:[],password:[],password_confirmation:[],avatar:[]}}},mounted:function(){},methods:{login:function(){var t=this;return Object(n.a)(regeneratorRuntime.mark((function e(){var form;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return form=document.getElementById("register"),form=new FormData(form),t.file&&form.append("avatar",t.file),t.$nuxt.$refs.loading.start(),t.formError.name=[],t.formError.password=[],t.formError.email=[],t.formError.password_confirmation=[],t.formError.avatar=[],e.next=11,c.a.post("/signup",form).then(function(){var e=Object(n.a)(regeneratorRuntime.mark((function e(r){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.$nuxt.$refs.loading.finish(),t.$message.success(r.data.message),e.next=4,l.set("token",r.data.access_token);case 4:return Echo.connector.pusher.config.auth.headers.Authorization="Bearer ".concat(r.data.access_token),c.a.defaults.headers.authorization="Bearer ".concat(r.data.access_token),e.next=8,t.getUser();case 8:t.$router.push({path:"/"});case 9:case"end":return e.stop()}}),e)})));return function(t){return e.apply(this,arguments)}}()).catch((function(e){if(console.log(e),t.$nuxt.$refs.loading.finish(),e.response){if(422==e.response.status){var r=e.response.data.errors;t.formError=r,t.$message.error(e.response.data.message)}}else t.$message.error("internet error")}));case 11:case"end":return e.stop()}}),e)})))()},getUser:function(){var t=this;return Object(n.a)(regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.$message.success("please wait afrew seconds while loading data"),t.$nuxt.$refs.loading.start(),e.next=4,c.a.get("/user").then((function(e){return t.$nuxt.$refs.loading.finish(),e.data})).catch((function(e){return t.$nuxt.$refs.loading.finish(),null}));case 4:$nuxt.user=e.sent;case 5:case"end":return e.stop()}}),e)})))()}}},f=(r(798),r(86)),component=Object(f.a)(d,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"mh-100 container justify-content-center"},[r("div",{staticClass:"row justify-content-center"},[r("div",{staticClass:"card shadow col-xl-4 col-lg-5 col-md-6 col-sm-10 col-12 p-3 mb-5 bg-white rounded"},[t._m(0),t._v(" "),r("div",{staticClass:"card-body"},[r("form",{staticClass:"col-md-12",attrs:{id:"register",autocomplete:"off"},on:{submit:function(e){return e.preventDefault(),t.login(e)}}},[r("div",{staticClass:"form-group row"},[r("div",{staticClass:"col-md-12"},[r("input",{staticClass:"form-control",attrs:{type:"text",name:"name",placeholder:"please enter your name",id:"name"}}),t._v(" "),t._l(t.formError.name,(function(e,n){return r("span",{key:n,staticClass:"text-danger"},[t._v(t._s(e))])}))],2)]),t._v(" "),r("div",{staticClass:"form-group row"},[r("div",{staticClass:"col-md-12"},[r("b-form-file",{attrs:{accept:"image/*",capture:!0,state:Boolean(t.file),placeholder:"Choose a file or drop it here...","drop-placeholder":"Drop file here..."},model:{value:t.file,callback:function(e){t.file=e},expression:"file"}}),t._v(" "),t._l(t.formError.avatar,(function(e,n){return r("span",{key:n,staticClass:"text-danger"},[t._v(t._s(e))])}))],2)]),t._v(" "),r("div",{staticClass:"form-group row"},[r("div",{staticClass:"col-md-12"},[r("input",{staticClass:"form-control",attrs:{type:"text",name:"email",id:"email",placeholder:"please enter your email"}}),t._v(" "),t._l(t.formError.email,(function(e,n){return r("span",{key:n,staticClass:"text-danger"},[t._v(t._s(e))])}))],2)]),t._v(" "),r("div",{staticClass:"form-group row"},[r("div",{staticClass:"col-md-12"},[r("input",{staticClass:"form-control",attrs:{type:"password",name:"password",id:"password",placeholder:"please enter your password "}}),t._v(" "),t._l(t.formError.password,(function(e,n){return r("span",{key:n,staticClass:"text-danger"},[t._v(t._s(e))])}))],2)]),t._v(" "),r("div",{staticClass:"form-group row"},[r("div",{staticClass:"col-md-12"},[r("input",{staticClass:"form-control",attrs:{type:"password",name:"password_confirmation",id:"password_confirmation",placeholder:"please confirm your password "}}),t._v(" "),t._l(t.formError.password_confirmation,(function(e,n){return r("span",{key:n,staticClass:"text-danger"},[t._v(t._s(e))])}))],2)]),t._v(" "),r("div",{staticClass:"form-group row"},[r("div",{staticClass:"col-md-12"},[r("button",{staticClass:"btn btn-primary uppercase"},[t._v("register")]),t._v(" "),r("nuxt-link",{staticClass:"btn btn-link",attrs:{to:"/login"}},[t._v("already have account")])],1)])])])])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"text-center"},[e("h3",{staticClass:"uppercase"},[this._v("Register")])])}],!1,null,"1f2e981c",null);e.default=component.exports}}]);