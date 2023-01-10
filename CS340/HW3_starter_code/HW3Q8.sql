CREATE TABLE 'bsg_spaceship'(

    'id' int(11) NOT NULL AUTO_INCREMENT,
    'name' varchar(255) NOT NULL,
    'seperate_saucer_section' BOOLEAN DEFAULT NO,
    'length' INTEGER NOT NULL 
    PRIMARY KEY('id'),
    KEY 'name' ('name'),
)

DESCRIBE bsg_spaceship