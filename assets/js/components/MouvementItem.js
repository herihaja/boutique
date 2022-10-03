    // ./assets/js/components/MouvementItem.js
    import React from 'react';
    import DatePicker, { registerLocale } from "react-datepicker";
    import fr from "date-fns/locale/fr";
    registerLocale("fr", fr)
     
    class MouvementItem extends React.Component {
        constructor (props) {
            super(props);
            this.produit = props.produit;
            this.produitNom = props.produitNom;
            this.quantite= props.quantite;
            this.prix = props.prix;
            this.clickDelete = props.clickDelete;
            this.id = props.id;
            this.datePeremption = props.datePeremption;
            this.unite = props.unite;
            this.state = {'quantite': props.quantite, 
            'unite': props.unite || Object.keys(props.prix)[0], 'total':0, 'datePeremption': ''};
            this.updateTotalState();
            this.isVente = props.isVente;
            this.datesPeremption = props.datesPeremption;
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

        selectedDatePeremption = (date) => {
            this.setState({'datePeremption': date});
            return date;
        }

        formatDate = (date) => {
            var l = date.split("/");
            return l.reverse().join("/");
        }

        render () {
            return (
            <tr>
                
                <td className="col">
                    <p><a href="#" onClick={ () => this.clickDelete(this)}><img src="/static/img/delete-icon.png" className='deleteProduit'/></a> &nbsp; {this.produitNom}</p>
                </td>
                
                <td className="col" data={this.datesPeremption.length}>
                    { this.isVente ? 
                        <select onChange={this.selectedDatePeremption} name="datePeremption[]">
                            {this.datesPeremption.map(
                                (value, key) => (
                                    <option value={ value } key={key}>{ value }</option>
                                )
                            )}
                        </select>
                     :
                        <DatePicker
                            selected={this.state.datePeremption}
                            onChange={(date) => this.selectedDatePeremption(date)}
                            timeInputLabel="Time:"
                            dateFormat="dd/MM/yyyy"
                            locale="fr"
                            name="datePeremption[]"
                        />
                    }
                    
                </td>
                <td className="col">
                    <input type="hidden" name="produit[]" value={this.id}/>
                    <input type="number" value={this.state.quantite} onChange={this.changeQuantite} name="quantite[]"/>
                </td>
                <td className="col"> 
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
                { this.isVente ? <>
                <td className="col" >
                    { this.prixUnitaire()}
                </td>
                <td className="col" >
                    { this.prixTotal()}
                </td>
                 </> :
                null
                }
                <td>&nbsp;</td>
            </tr>
        )};
    };
    
    export default MouvementItem;