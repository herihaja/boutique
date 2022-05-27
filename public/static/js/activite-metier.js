var activiteNode = function (i, metierId) {
    return '<div class="card"><div class="card-body">'+
                                         '<div class="row">'+
                                            '<div class="col-md-12">'+
                                                    '<div class="form-group">'+
                                                        '<label>Nom de l\'activit&eacute;</label>'+
                                                        '<input type="text" id="metier_activites_' + i + '_name" name="metier[activites][' + i + '][name]" required="required" maxlength="150" class="form-control" value="" />'+
                                                        
                                                    '</div>'+
                                            '</div>'+
                                        '</div>'+

                                       '<div class="row">'+
                                            '<div class="col-md-12">'+
                                                    '<div class="form-group">'+
                                                        '<label>Finalit&eacute;</label>'+
                                                        '<textarea id="metier_activites_' + i + '_finalite" name="metier[activites][' + i + '][finalite]" required="required" class="form-control"></textarea>'+
                                                        
                                                    '</div>'+
                                            '</div>'+
                                        '</div>'+

                                         '<div class="row">'+
                                            '<div class="col-md-12">'+
                                                    '<div class="form-group">'+
                                                        '<label>Collaborateur</label>'+
                                                        '<textarea id="metier_activites_' + i + '_collaborateurs" name="metier[activites][' + i + '][collaborateurs]" required="required" class="form-control"></textarea>'+
                                                        
                                                    '</div>'+
                                            '</div>'+
                                        '</div>'+

                                         '<div class="row">'+
                                            '<div class="col-md-12">'+
                                                    '<div class="form-group">'+
                                                        '<label>Ordre</label>'+
                                                        '<input type="number" id="metier_activites_' + i + '_ordre" name="metier[activites][' + i + '][ordre]" required="required" class="form-control" value="' + i + '" />'+
                                                        '<input type="hidden" id="metier_activites_' + i + '_ordre" name="metier[activites][' + i + '][ordre]" required="required" class="form-control" value="' + metierId + '" />'+
                                                    '</div>'+
                                            '</div>'+
                                        '</div>'+

                                        '</div></div>';}
                                        
 

