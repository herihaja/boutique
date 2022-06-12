    // ./assets/js/components/AchatItem.js
    import React from 'react';
     
    class AchatItem extends React.Component {
        constructor (props) {
            super(props);
            this.produit = props.produit;
            this.produitNom = props.produitNom;
            this.quantite= props.quantite;
            this.prix = props.prix;
            this.clickDelete = props.clickDelete;
            this.id = props.id;
            this.unite = props.unite;
            this.state = {'quantite': props.quantite, 
            'unite': props.unite || Object.keys(props.prix)[0], 'total':0};
            this.updateTotalState();
        }

        updateTotalState = () => {
            var total = this.prixTotal();
            this.props.addPrixToTotal(total);
        }

        prixTotal = () => {
            return this.prixUnitaire() * this.state.quantite;
        }

        prixUnitaire = () => {
            return this.prix[this.state.unite]['valeur'];
        }

        changeQuantite = (e) =>  {
            this.oldValue = this.prixTotal();
            this.setState({'quantite': e.target.value}, this.updateTotalCallBack);
        }

        changeUnite = (e) => {
            this.oldValue = this.prixTotal();
            this.setState({'unite': e.target[e.target.selectedIndex].text}, this.updateTotalCallBack);
        }

        updateTotalCallBack = () => {
            var diff = this.prixTotal() - this.oldValue;
            this.props.addPrixToTotal(diff);
        }

        selectedPrix = () => {
            return this.prix[this.state.unite].prixId;
        }

        render () {
            return (
            <tr>
                <td className="col-2">
                    <p><a href="#" onClick={ () => this.clickDelete(this)}><img src="/static/img/delete-icon.png" className='deleteProduit'/></a> &nbsp; {this.produitNom}</p>
                </td>
                <td className="col-2">
                    <input type="hidden" name="produit[]" value={this.id}/>
                    <input type="number" value={this.state.quantite} onChange={this.changeQuantite} name="quantite[]"/>
                </td>
                <td className="col-2">
                    <input type="hidden" name="prixId[]" value={this.selectedPrix()}/>
                    <input type="hidden" name="total[]" value={this.prixTotal()}/>
                    <select onChange={this.changeUnite} name="unite[]">
                        {Object.keys(this.prix).map(
                            (key, value) => (
                            
                        <option value={ this.prix[key].id } key={key}>{ key }</option>
                            )
                        )}
                    </select>
                </td>
                <td className="col-3">
                    { this.prixUnitaire()}
                </td>
                <td className="col-3">
                    {this.prixTotal() }
                </td>
                
            </tr>
        )};
    };
    
    export default AchatItem;