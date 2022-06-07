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
        var id = achatItem.index;
        var entries = this.state.entries;
        var toSelect = entries[id];
        var toSelectProduits = this.state.toSelectProduits;
        //toSelectProduits.append();
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

        entries.splice(id, 1);
        this.addPrixToTotal(-achatItem.prixTotal());
        this.setState({entries, toSelectProduits});
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
                {/*-- Todo: to herihaja cleanup */}
                <input type="button" className='d-none' onClick={this.addItem} value="Add field"/>
                <input type="text" className="d-none" value={this.state.AchatName} onChange={this.changeAchatName} /> 
                <textarea name="description" className="d-none" defaultValue={this.state.description}></textarea>
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
                            ({ id, produit, produitNom, unite, quantite, prix }, index) => {
                                let achatItem = (<AchatItem
                                    key={index+1}
                                    index={index}
                                    id={id}
                                    produit={produit}
                                    produitNom={produitNom}
                                    unite={unite.value}
                                    quantite={quantite}
                                    prix={prix}
                                    prixTotal={this.prixTotal}
                                    clickDelete={this.clickDeleteItem}
                                    addPrixToTotal={this.addPrixToTotal}
                                />);
                                
                                return achatItem;
                            }
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
                            <td>{this.state.total}</td>
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