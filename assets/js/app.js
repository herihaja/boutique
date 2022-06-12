import '../styles/app.css';

// start the Stimulus application
import '../bootstrap';


import React, {StrictMode} from 'react';
import AchatItem from './components/AchatItem';
import { createRoot } from 'react-dom/client';
import { Modal, Button, Form } from "react-bootstrap";
import Select from "react-select";



 class Achat extends React.Component {
    constructor() {
        super();

        this.state = {
            entries: [],
            AchatName: 'Achat default',
            description: 'Lorem ipsum',
            showAddItem: false,
            editedItem: {'produit': 0, 'quantite': 0, 'unite': 0, 'prixUnitaire':0},
            toSelectProduits: [{'id': 1, 'nom': 'Kiraro'}, {'id':2, 'nom': 'Mofo'}],
            allProducts: [],
            total:0
        };
        
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

    changeAchatName = (e) => {
        this.setState({AchatName: e.target.value});
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

    updateEditedItemUnite = (selected) => {
        var editedItem = this.state.editedItem;
        editedItem.unite = selected;
        this.setState(editedItem);
    }

    

    saveItemForm = () => {
        var entries = this.state.entries; 
        var edited = JSON.parse(JSON.stringify(this.state.editedItem));
        entries.push(edited);
        this.setState({'entries': entries, 'showAddItem': false})
    }

    selectProduit = (e) => {
        var toSelectProduits = this.state.toSelectProduits;
        var entries = this.state.entries;
        var selected = false;
        for (const index in toSelectProduits){
            if (toSelectProduits[index].id == e.target.value) {
                var selected = toSelectProduits[index];
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
        this.setState({total});
    }
    
    render () { 
        let total = 0;
        return (
            <div className="Achat-container">
                <table className="table table-hover table-striped" id="liste-table">
                    <thead>
                        <tr>
                                    <th>Produit</th>
                                    <th>Nombre</th>
                                    <th>Unité</th>
                                    <th>PU</th>
                                    <th>Total</th>
                                
                        </tr>
                    </thead>
                    <tbody>
                        {this.state.entries.map(
                            ( product , index) => (
                                <AchatItem
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
                            <td></td>
                            <td>
                                <input type="hidden" name="grandTotal" value={this.state.total}/>
                                {this.state.total}
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <StrictMode>
                
                </StrictMode>
            </div>
        );
    }
    
}

const rootElement = document.getElementById("AchatItem-list");
createRoot(rootElement).render(<Achat />);