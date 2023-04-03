CREATE TABLE fakulta
( idBloku INT NOT NULL AUTO_INCREMENT,
  nazovBloku VARCHAR(10) NOT NULL,
  pocetPoschodi INT NOT NULL,
  PRIMARY KEY (idBloku)
) ENGINE=InnoDB;

CREATE TABLE ucebne
( idUcebne INT NOT NULL AUTO_INCREMENT,
  idBloku INT NOT NULL,
  nazovUcebne VARCHAR(10) NOT NULL,
  velkost INT,
  PRIMARY KEY (idUcebne),
  CONSTRAINT fk_ucebne
    FOREIGN KEY (idBloku)
    REFERENCES fakulta(idBloku)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `fakulta` (`idBloku`, `nazovBloku`, `pocetPoschodi`) VALUES
(1, 'A-blok', 8),
(2, 'B-blok', 7),
(3, 'C-blok', 7),
(4, 'D-blok', 7),
(5, 'E-blok', 8),
(6, 'T-blok', 2);

INSERT INTO `ucebne` (`idUcebne`, `idBloku`, `nazovUcebne`, `velkost`) VALUES
(1, 1, 'A801', 40),
(2, 1, 'A806', 20),
(3, 2, 'B301', 20),
(4, 2, 'B305', 15),
(5, 4, 'D405', 10),
(6, 4, 'D406', 10),
(7, 6, 'C119', 20),
(8, 4, 'D010', 40);

CREATE TABLE osoby
( id INT NOT NULL AUTO_INCREMENT,
  meno VARCHAR(30) NOT NULL,
  vek INT NOT NULL,
  pohlavie VARCHAR(3) NOT NULL,
  opis VARCHAR(30) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

INSERT INTO `osoby` (`id`, `meno`, `vek`, `pohlavie`, `opis`) VALUES
(1, 'Jano', 23, 'M', 'student'),
(2, 'Fero', 18, 'M', 'student'),
(3, 'Alena', 20, 'Z', 'studentka'),
(4, 'Juro', 19, 'M', 'student');