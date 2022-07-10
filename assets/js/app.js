import '../styles/app.scss';
import '../styles/app.css';
// start the Stimulus application
import '../bootstrap';


import React, {StrictMode} from 'react';
import MouvementItem from './components/MouvementItem';
import { createRoot } from 'react-dom/client';
import { Modal, Button, Form } from "react-bootstrap";
import Select from "react-select";



 class Mouvement extends React.Component {
    constructor(props) {
        super();

        this.state = {
            entries: [],
            MouvementName: 'Mouvement default',
            description: 'Lorem ipsum',
            toSelectProduits: [{'id': 1, 'nom': 'Kiraro'}, {'id':2, 'nom': 'Mofo'}],
            allProducts: [],
            total:0,
            montantRendu: 0
        };
        this.isVente = props.operation == "vente" ;
    }

    refsCollection = {};

    componentDidMount = () => {
        fetch(produitListUrl)
            .then(response => response.json())
            .then(toSelectProduits => {
                
                var allProducts = JSON.parse(JSON.stringify(toSelectProduits));
                this.setState({
                    'allProducts': allProducts
                });

                this.setState({
                    'toSelectProduits':toSelectProduits
                });
            });
    }

    changeMouvementName = (e) => {
        this.setState({MouvementName: e.target.value});
    }

    clickDeleteItem = (achatItem) => {
        var id = achatItem.id;
        var entries = this.state.entries;
        var toSelect = entries.filter((item)=> {
            if (item.id == id)
                return true;
            return false;
        })[0];
        
        var toSelectProduits = this.state.toSelectProduits;
        var allProducts = this.state.allProducts;
        var toGetBack = false;
        for (var index in allProducts) {
            if (allProducts[index].id == toSelect.produit){
                toGetBack = allProducts[index];
                break;
            }
        }
        if (toGetBack)
            toSelectProduits.push(toGetBack);

        entries = entries.filter((item)=> {
                if (item.id == id)
                    return false;
                return true;
            });
        this.addPrixToTotal(-achatItem.prixTotal());
        this.setState({entries: entries, toSelectProduits: toSelectProduits});
    }

    selectProduit = (e) => {
        var toSelectProduits = this.state.toSelectProduits;
        var entries = this.state.entries;
        var selected = false;
        for (const index in toSelectProduits){
            if (toSelectProduits[index].id == e.target.value) {
                var selected = toSelectProduits[index];
                /********* todo: herihaja  if (this.isVente)     ***/
                toSelectProduits.splice(index, 1);
                break;
            }
        }
        if (selected) {
            var prices = selected.prices.split("|");
            selected['prix'] = [];
            for(var i in prices) {
                var prix = prices[i].split(";");
                selected['prix'][prix[1]] = [];
                selected['prix'][prix[1]]['valeur'] = parseFloat(prix[0]);
                selected['prix'][prix[1]]['id'] = prix[2]; //id parametre valeur
                selected['prix'][prix[1]]['prixId'] = prix[3]; // id prix
                if (typeof(selected['unite'])== "undefined")
                    selected['unite'] = prix[1];
            }
            entries.push({
                'id': selected.id,
                'produit': selected.id, 
                'quantite': 1, 
                'prix': selected.prix, 
                'unite':selected.unite,
                'produitNom': selected.nom
            });
        }
        this.setState({toSelectProduits, entries});
        return false;
    }

    addPrixToTotal = (prix) => {
        var total = this.state.total;
        total += prix;
        this.setState({total}, this.getMontantRendu);
    }

    getMontantRendu = () => {
        var montantRendu = !isNaN(this.state.montantRemis) ? this.state.montantRemis - this.state.total :0 ;
        this.setState({montantRendu});
    }

    changeMontantRemis = (e) => {
        var montantRemis = e.target.value;
        this.setState({montantRemis}, this.getMontantRendu);     
    }

    isFormValid = () => {
        return this.isVente ? (this.state.montantRendu < 0 || this.state.total == 0) : !this.state.entries.length;
    }
    
    render () { 
        let total = 0;
        return (
            <div className="Mouvement-container">
                <table className="table table-hover table-striped" id="liste-table">
                    <thead>
                        <tr>
                            
                                    <th>Produit</th>
                                    <th>Nombre</th>
                                    <th>Unité</th>
                                    { this.isVente ? <>
                                    <th>PU</th>
                                    <th>Total</th></> : null }
                                <th>&nbsp;</th>
                                
                        </tr>
                    </thead>
                    <tbody>
                        {this.state.entries.map(
                            ( product , index) => (
                                <MouvementItem
                                    key={product.id}
                                    id={product.id}
                                    produit={product.id}
                                    produitNom={product.produitNom}
                                    unite={product.unite}
                                    quantite={product.quantite}
                                    prix={product.prix}
                                    prixTotal={this.prixTotal}
                                    clickDelete={this.clickDeleteItem}
                                    addPrixToTotal={this.addPrixToTotal}
                                    isVente={this.isVente}
                                />
                            )
                        )}
                        <tr>
                            <td colSpan="2">
                                <select id="produitSelect" onChange={this.selectProduit}>
                                    <option value="">-- Selectionnez Produit</option>
                                    
                                        {this.state.toSelectProduits.map(
                                            (toSelect, index) => (
                                            
                                        <option value={ toSelect.id } key={toSelect.id}>{ toSelect.nom }</option>
                                            )
                                        )}
                                    
                                </select>
                            </td>
                            <td></td>
                            { this.isVente ? <>
                            <td>Total:</td>
                            <td>
                                <input type="hidden" name="grandTotal" value={this.state.total}/>
                                {this.state.total}
                            </td></>: null}
                            <td>&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                { this.isVente ? <>
                <div className="row">
                    <div className="col-md-6">
                        <div className="form-group">
                            <label>Montant rémis</label>
                                <input type="number" className="form-control" name="montantRemis" required="required" onChange={this.changeMontantRemis}/>
                        </div>
                    </div>
                    <div className="col-md-6">
                        <div className="form-group">
                            <label>Montant rendu</label>
                                <input type="number" className="form-control" name="montantRendu" min="0" readOnly="readonly" value={this.state.montantRendu}/>
                        </div>
                    </div>
                </div></>:null}
                <div className="row save-button" >
                    <div className="col-md-12">
                        <button type="submit" className="btn btn-info btn-fill pull-right save-btn" disabled={this.isFormValid()}>Enregistrer</button>
                    </div>
                </div>
                <StrictMode>
                
                </StrictMode>
            </div>
        );
    }
    
}

const rootElement = document.getElementById("MouvementItem-list");
createRoot(rootElement).render(<Mouvement operation={rootElement.getAttribute('data-param')}/>);