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
            editedItem: {'produit': 0, 'quantite': 0, 'unite': 0, 'prixUnitaire':0}
        };
    }

    componentDidMount = () => {
        fetch('https://localhost:8000/Achat-AchatItem/')
            .then(response => response.json())
            .then(entries => {
                this.setState({
                    entries,
                });
            });
    }

    changeAchatName = (e) => {
        this.setState({AchatName: e.target.value});
    }

    clickDeleteItem = (id) => {
        var entries = this.state.entries;
        entries.splice(id, 1);
        this.setState({entries: entries});
    }

    addItem = () => {
        if (!this.state.showAddItem)
        this.setState({showAddItem: true});
        else
        this.setState({showAddItem: false});
    }

    closeItemModal = () => {
        this.setState({showAddItem: false});
    }

    updateEditedItemProduit = (selected) => {
        var editedItem = this.state.editedItem;
        editedItem.produit = selected;
        this.setState(editedItem);
    }

    updateEditedItemUnite = (selected) => {
        var editedItem = this.state.editedItem;
        editedItem.unite = selected;
        this.setState(editedItem);
    }

    updateEditedItemQuantite = (e) => {
        var editedItem = this.state.editedItem;
        editedItem.quantite = e.target.value;
        this.setState({'editedItem': editedItem});
    }

    itemTypeOptions = [
        {'value':'text', 'label': 'Text'}, 
        {'value':'number', 'label': 'Number'}, 
        {'value':'date', 'label': 'Date'}, 
        {'value':'relation', 'label':'Relation'},
    ];

    saveItemForm = () => {
        var entries = this.state.entries; 
        var edited = JSON.parse(JSON.stringify(this.state.editedItem));
        entries.push(edited);
        this.setState({'entries': entries, 'showAddItem': false})
    }

    
    render () { 
        return (
            <div className="Achat-container">
                <input type="button" onClick={this.addItem} value="Add field"/>
                <input type="text" value={this.state.AchatName} onChange={this.changeAchatName} /> 
                <textarea name="description" defaultValue={this.state.description}></textarea>

                {this.state.entries.map(
                     ({ id, produit, unite, quantite, prixUnitaire }, index) => (
                        <AchatItem
                        key={index+1}
                        index={index+1}
                        id={id}
                        produit={produit}
                        unite={unite.value}
                        quantite={quantite}
                        prixUnitaire={prixUnitaire}
                        clickDelete={this.clickDeleteItem}
                    >
                    </AchatItem>
                     )
                 )}
                <StrictMode>
                <Modal show={this.state.showAddItem} animation={true} onHide={this.closeItemModal} backdrop="static">

                    <Modal.Header closeButton>
                        <Modal.Title>Produit</Modal.Title>
                    </Modal.Header>
            
                    <Modal.Body>
                        <label>Produit: </label>
                        <Select defaultValue={this.state.editedItem.produit} value={this.state.editedItem.produit} onChange={this.updateEditedItemProduit} options={this.fieldTypeOptions}>
                        </Select>
                        <br/>
                        <label>Unit&eacute;: </label>
                        <Select defaultValue={this.state.editedItem.unite} value={this.state.editedItem.unite} onChange={this.updateEditedItemUnite} options={this.fieldTypeOptions}>
                        </Select>
                        <br/>
                        <label>Quantité: </label>
                        <input type="text" value={this.state.editedItem.quantite}  onChange={this.updateEditedItemQuantite}/> 
                        <br/>
                        <label>Prix unitaire: </label>
                        <input type="text" value={this.state.editedItem.prixUnitaire}  readOnly="readonly"/> 
                        <label>Prix: </label>
                        <input type="text" value={this.state.editedItem.prix}  readOnly="readonly"/> 
                        
                        
                    </Modal.Body>
            
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.saveItemForm}>Secondary</Button>
                        <Button variant="primary" onClick={this.saveItemForm}>Primary</Button>
                    </Modal.Footer>
            
                </Modal>
                </StrictMode>
            </div>
        );
    }
    
}

const rootElement = document.getElementById("AchatItem-list");
createRoot(rootElement).render(<Achat />);