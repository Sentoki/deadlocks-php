CREATE TABLE account (
  id INTEGER,
  amount FLOAT,
  CONSTRAINT account_id_pk PRIMARY KEY (id)
);

CREATE SEQUENCE account_id_seq;
ALTER TABLE account ALTER id SET DEFAULT nextval('account_id_seq');

CREATE TABLE operation (
  id INTEGER,
  amount FLOAT,
  account_from_id INTEGER REFERENCES account(id),
  account_to_id INTEGER REFERENCES account(id),
  CONSTRAINT operation_id_pk PRIMARY KEY (id)
);
CREATE SEQUENCE operation_id_seq;
ALTER TABLE operation ALTER id SET DEFAULT nextval('operation_id_seq');

INSERT INTO account (amount) VALUES (1000000);
INSERT INTO account (amount) VALUES (1000000);