CREATE TABLE role (
	role_id INTEGER NOT NULL AUTO_INCREMENT,
	role_name VARCHAR(50) NOT NULL,
	
	PRIMARY KEY (role_id)
);

CREATE TABLE permission (
	permission_id INTEGER NOT NULL AUTO_INCREMENT,
	permission_name VARCHAR(50) NOT NULL,
	PRIMARY KEY (permission_id)
);

CREATE TABLE resource (
	resource_id INTEGER NOT NULL AUTO_INCREMENT,
	resource_name VARCHAR(50) NOT NULL,
	PRIMARY KEY (resource_id)
);

CREATE TABLE role_resource_permission (
	role_id INTEGER NOT NULL,
	resource_id INTEGER NOT NULL,
	permission_id INTEGER NOT NULL,
	FOREIGN KEY (role_id) REFERENCES role(role_id),
	FOREIGN KEY (resource_id) REFERENCES resource(resource_id),
	FOREIGN KEY (permission_id) REFERENCES permission(permission_id)
);

ALTER TABLE employee
ADD roleId int(11)
