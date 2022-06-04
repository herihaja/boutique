    // ./assets/js/components/AchatItem.js
    import React from 'react';
     
    const AchatItem = ({ id, produit, unite, quantite, prixUnitaire, clickDelete , index}) => {
        

        return (
            <div className="row">
                <div key={id} className="col-2">
                    <p>{produit} ({id})</p>
                </div>
                <div className="col-2">
                    {quantite}
                </div>
                <div className="col-3">
                    {prixUnitaire}
                </div>
                <div className="col-3">
                    {prixUnitaire * quantite}
                </div>
                <div className="col-2">
                    <a href="#" onClick={ () => clickDelete(index)}> Delete</a>
                </div>
            </div>
        );
    };
    
    export default AchatItem;