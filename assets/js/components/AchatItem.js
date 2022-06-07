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
            this.index = props.index;
            this.state = {'quantite': props.quantite, 
            'unite': props.unite || Object.keys(props.prix)[0], 'total':0};
            this.updateTotalState();
        }

        updateTotalState = () => {
            var total = this.prix[this.state.unite] * this.state.quantite;
            this.props.addPrixToTotal(total);
            this.props.returnState(this.state);
        }

        prixTotal = () => {
            return this.prix[this.state.unite] * this.state.quantite;
        }

        prixUnitaire = () => {
            return this.prix[this.state.unite];
        }

        changeQuantite = (e) =>  {
            this.oldValue = this.prixTotal();
            this.setState({'quantite': e.target.value}, this.updateTotalCallBack);
        }

        changeUnite = (e) => {
            this.oldValue = this.prixTotal();
            this.setState({'unite': e.target.value}, this.updateTotalCallBack);
        }

        updateTotalCallBack = () => {
            var diff = this.prixTotal() - this.oldValue;
            this.props.addPrixToTotal(diff);
        }

        render () {
            return (
            <tr>
                <td className="col-2">
                    <p><a href="#" onClick={ () => this.clickDelete(this)}><img src="/static/img/delete-icon.png" className='deleteProduit'/></a> &nbsp; {this.produitNom}</p>
                </td>
                <td className="col-2">
                    <input type="number" value={this.state.quantite} onChange={this.changeQuantite}/>
                </td>
                <td className="col-2">
                    {this.unite}
                    <select id="selectUnite" onChange={this.changeUnite}>
                        {Object.keys(this.prix).map(
                            (unite, index) => (
                            
                        <option value={ unite } key={index}>{ unite }</option>
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