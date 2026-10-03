-- Created in MSSMS

IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'McNeeseBookstore')
BEGIN
    CREATE DATABASE McNeeseBookstore;
END
GO

-- Create Schemas
CREATE SCHEMA UserSchema;
GO
CREATE SCHEMA Product;
GO
CREATE SCHEMA Sales;
GO
CREATE SCHEMA Payment;
GO


-- Users Schema
CREATE TABLE UserSchema.Users (
    UserID INT IDENTITY(1,1) PRIMARY KEY,
    FirstName NVARCHAR(50) NOT NULL,
    LastName NVARCHAR(50) NOT NULL,
    Email NVARCHAR(255) NOT NULL UNIQUE,
    Password NVARCHAR(255) NOT NULL, 
    Address NVARCHAR(MAX),
    Phone NVARCHAR(20),
    Role NVARCHAR(20) NOT NULL CHECK (Role IN ('Customer', 'Admin')),
    CreatedAt DATETIME DEFAULT GETDATE()
);

-- Product Schema
CREATE TABLE Product.Categories (
    CategoryID INT IDENTITY(1,1) PRIMARY KEY,
    CategoryName NVARCHAR(50) NOT NULL,
    Description NVARCHAR(MAX) NULL
);

CREATE TABLE Product.Products (
    ProductID INT IDENTITY(1,1) PRIMARY KEY,
    ProductName NVARCHAR(255) NOT NULL,
    Description NVARCHAR(MAX),
    Price DECIMAL(10,2) NOT NULL,
    Stock INT NOT NULL DEFAULT 0,
    CategoryID INT NOT NULL,   
    ProductType NVARCHAR(50) NOT NULL CHECK (
		ProductType IN (
		'Writing Supplies', 'Paper Products & Notebooks', 'Organization & Storage', 
		'Calculation & Measurement', 'Planner', 'Art & Craft Supplies', 'Merchendise')),  
    ProductLink NVARCHAR(255),  
    FOREIGN KEY (CategoryID) REFERENCES Product.Categories(CategoryID)
);

-- Sales Schema

-- Carts table: holds a customer's active shopping cart
-- Each user has only one active cart
-- Helps find active cart assosicated with an individual user
CREATE TABLE Sales.Cart (
    CartID INT IDENTITY(1,1) PRIMARY KEY,
    UserID INT NOT NULL,
    FOREIGN KEY (UserID) REFERENCES UserSchema.Users(UserID)
);

-- CartItems table: holds the individual items in a customer's cart
CREATE TABLE Sales.CartItems (
    CartItemID INT IDENTITY(1,1) PRIMARY KEY,
    CartID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (CartID) REFERENCES Sales.Cart(CartID),
    FOREIGN KEY (ProductID) REFERENCES Product.Products(ProductID)
);

-- Orders table: holds status of finalized orders
CREATE TABLE Sales.Orders (
    OrderID INT IDENTITY(1,1) PRIMARY KEY,
    UserID INT NOT NULL,
    OrderDate DATETIME DEFAULT GETDATE(),
    TotalPrice DECIMAL(10,2) NOT NULL,
    Status NVARCHAR(50) NOT NULL CHECK (
		Status IN (
			'Package Shipped', 
			'Delayed', 
			'Delivery On Its Way', 
			'Delivered', 
			'Cancelled')),
    FOREIGN KEY (UserID) REFERENCES UserSchema.Users(UserID)
);

-- OrderItems table: holds individual products within an order
CREATE TABLE Sales.OrderItems (
    OrderItemID INT IDENTITY(1,1) PRIMARY KEY,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL DEFAULT 1,
    Price DECIMAL(10,2) NOT NULL,  
    FOREIGN KEY (OrderID) REFERENCES Sales.Orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product.Products(ProductID)
);

-- Payment Schema

-- PaymentMethods table: stores customer's saved payment information
CREATE TABLE Payment.PaymentMethods (
    PaymentMethodID INT IDENTITY(1,1) PRIMARY KEY,
    UserID INT NOT NULL,
    PaymentType NVARCHAR(50) NOT NULL CHECK (
		PaymentType IN (
			'CreditCard', 
			'DebitCard', 
			'PayPal')),
    CardHolderName NVARCHAR(50),
    EncryptedCardNumber VARBINARY(MAX) NOT NULL,
    ExpirationMonth INT NOT NULL,
    ExpirationYear INT NOT NULL,
    BillingAddress NVARCHAR(MAX),
    Token NVARCHAR(255) NULL,
    FOREIGN KEY (UserID) REFERENCES UserSchema.Users(UserID)
);

-- Payments table: records payment transactions for orders
CREATE TABLE Payment.Payments (
    PaymentID INT IDENTITY(1,1) PRIMARY KEY,
    OrderID INT NOT NULL,
    PaymentMethodID INT NOT NULL,
    PaymentAmount DECIMAL(10,2) NOT NULL,
    PaymentDate DATETIME DEFAULT GETDATE(),
    PaymentStatus NVARCHAR(50) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Sales.Orders(OrderID),
    FOREIGN KEY (PaymentMethodID) REFERENCES Payment.PaymentMethods(PaymentMethodID)
);

-- Sales Schema (Order Tracking)
CREATE TABLE Sales.OrderHistory (
    HistoryID INT IDENTITY(1,1) PRIMARY KEY,
    OrderID INT NOT NULL,
    Status NVARCHAR(50) NOT NULL,
    Comment NVARCHAR(MAX) NULL,
    ChangeDate DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (OrderID) REFERENCES Sales.Orders(OrderID)
);
