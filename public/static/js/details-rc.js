$(document).ready(function() {
  getAggregatedData = function(detailObject){
    var [labelList, idList] = [[], []];
    $.each(detailObject, function(index, value){
      labelList.push(value.label);
      idList.push(value.id)
    });
    return [labelList.join(", "), idList];
  }

  getMultipleSelected = function (element) {
    var liste = [];
    element.find("option[selected=selected]").each(function(index){
      liste.push({'id': $(this).val(), 'label': $(this).html()});
    });
    return liste;
  }

  getSingleSelected = function(selectedElement){
    var selected = selectedElement.val();
    var label = selectedElement.find("option[value=" +selected+ "]").html();
    return {'id': selected, 
    'label': label};
  }

  getCurrentDetail = function(currentDetail) {
    if (typeof(currentDetail.id) == "undefined") {
      var selectedElement = $("#realisation_rc_module_agenda");
      
      var currentDetail = {'module_agenda': getSingleSelected(selectedElement),
        'collaborateurs': getMultipleSelected($("#realisation_rc_collaborateurs")),
        'materiels': getMultipleSelected($("#realisation_rc_materiels")),
        'id': $("#id_realisation_rc").val(),
        'previsionRealisation': app.previsionRealisation,
      };
    }

    [currentDetail.concatenatedCollaborateurs, currentDetail.collaborateursIds] = getAggregatedData(currentDetail.collaborateurs);
    [currentDetail.concatenatedMateriels, currentDetail.materielsIds] = getAggregatedData(currentDetail.materiels);
    return currentDetail;
  }


    Vue.component('detail-item', {
    template: '\
      <li>\
        {{ module_agenda }}\
        <button v-on:click="$emit(\'remove\')">Remove</button>\
      </li>\
    ',
    props: ['module_agenda']
  })
  
  app = new Vue({
    el: '#prereal-details',
    delimiters: ['[[', ']]'],
    data: function(){ return {
      newTodoText: '',
      details: {}
        
      ,
      previsionRealisation: 0
    }},
    methods: {
      addNewDetail: function (cle, detail) {
        var nObjet = {}
        detail.id = cle;
        nObjet[cle] = detail;
        for (i in this.details)
          if (i!= cle)
           nObjet[i] = this.details[i];

        this.details = nObjet;
        this.newTodoText = ''
      },
      removeDetail: function(detail) {
        var nObjet = {}

        for (i in this.details)
          if (i!= detail.id)
           nObjet[i] = this.details[i];

        this.details = nObjet;
      }
    },
    created() {
        that = this;
        data = {};
        var pageData = JSON.parse(document.getElementById('pageData').innerHTML);
        data.previsionRealisation = pageData.previsionRealisation;
        var details = {};
        
        for( index in pageData.details){ //$.each(data.details, function(index, value){
          var value = pageData.details[index]; 
          var detail = getCurrentDetail(value);
          
          if ((detail.module_agenda) != "undefined")
            details[index] = detail; 
        };
        
        data.details = details;//.details = details;
        data.previsionRealisation = pageData.previsionRealisation;
        that.postDetailUrl = pageData.postDetailUrl;
        that.deleteDetailUrl = pageData.deleteDetailUrl;
        that.details = details;
    }

  });

  /*  Click on 'enregistrer', will post the realisationRC open in the lightbox   */
  $("#save-detail").bind('click', function(){
    var detail = getCurrentDetail({});
    axios.post(app.postDetailUrl, detail
                            ).then( response => {
                                app.addNewDetail(response.data, detail);
                                $("#modalDetails").modal("toggle");
                            }).catch(err => {
                                if (err.response) {
                                  // client received an error response (5xx, 4xx)
                                } else if (err.request) {
                                  // client never received a response, or request never left
                                } else {
                                  // anything else
                                }
                            });
    
    
  });

  /* clear form and open lightbox  */
  $("#add-detail").bind('click', function(){
    $("#id_realisation_rc").val(0);
    $("#realisation_rc_collaborateurs option").removeAttr("selected");
    $("#realisation_rc_materiels option").removeAttr("selected");
    selectMS.multiSelect('refresh');
    $("#modalDetails").modal("toggle");
  });

/** edit realisationRC in lightbox */
  edit_detail_method = function(){
    var idDetail = $(this).attr("alt");
    $("#realisation_rc_collaborateurs option").removeAttr("selected");
    $("#realisation_rc_materiels option").removeAttr("selected");
    $("#id_realisation_rc").val(idDetail);
    var toOpen = app.details[idDetail];
    
    for (index in toOpen.collaborateursIds) {
      var id = toOpen.collaborateursIds[index];
      $("#realisation_rc_collaborateurs option[value=" + id +"]").attr("selected", "selected");
    }
    for (index in toOpen.materielsIds) {
      var id = toOpen.materielsIds[index];
      $("#realisation_rc_materiels option[value=" + id +"]").attr("selected", "selected");
    }
    $("#realisation_rc_module_agenda option[value="+ toOpen.module_agenda.id +"]").attr("selected", "selected");
    selectMS.multiSelect('refresh');
    $("#modalDetails").modal("toggle");
  }
  $("#prereal-details").on("click", ".edit-detail", edit_detail_method);


  /* deleting realisaation rc (detail) */
  delete_detail_method = function(){
    if (confirm("Etes vous sur de vouloir supprimer?")){
      var detail = {'id': $(this).attr("alt")};
      axios.post(app.deleteDetailUrl, detail
        ).then( response => {
            console.log(response);
            app.removeDetail(detail);
        }).catch(err => {
            if (err.response) {
              // client received an error response (5xx, 4xx)
            } else if (err.request) {
              // client never received a response, or request never left
            } else {
              // anything else
            }
      });
    }

  }
  $("#prereal-details").on("click", ".delete-detail", delete_detail_method);


  /*  axios initialisation  */
   axios.defaults.xsrfCookieName = 'csrftoken';
   axios.defaults.xsrfHeaderName = 'X-CSRFToken';
   axios.interceptors.request.use( (config) => {
       // Do something before request is sent
       //$("div.spanner").addClass("show");
       
       $('#overlay').fadeIn();
       return config;
   });

   axios.interceptors.response.use(function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    $('#overlay').fadeOut();
    return response;
  }, function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
  });
});