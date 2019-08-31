
module main;

    reg [2:0] addrA;
    reg [2:0] addrB;
    reg [2:0] addrI;
    
    reg CLK = 1'b1;
    wire [7:0] OUT1,OUT2;
    reg [7:0] IN;
    reg RESET;
	reg WRITE_EN;

    regFile myReg(IN, OUT1, OUT2, addrI ,addrA, addrB, CLK, RESET, WRITE_EN);
    
    initial begin

        #0
        RESET <= 0; 
        WRITE_EN <= 0; 
		
        #10
        RESET <= 1; 
        
        #20
        RESET <= 0; 
        
        #25
        // Assign values for address selectors
        addrI <= 3'b000;
        addrA <= 3'b000;
        addrB <= 3'b001;

        // Write IN value for the register addrI
        WRITE_EN <= 1; 
		IN <= 8'b00000100;
        
        #30
        // Write IN value for the register addrI
        addrI <= 3'b001;
        IN <= 8'b00000010;
        
		#32
		WRITE_EN <= 0;
		
        #35
   		$display("@35"); 
        $display("Output 1 = %b" , OUT1); 
        $display("Output 2 = %b\n" , OUT2);
        
        #40
        // Write IN value for the register addrI
        addrI <= 3'b001;
        IN <= 8'b00001010;
		WRITE_EN <=1;         
   
		#42
		WRITE_EN <= 0;
		
		#45
        $display("@45"); 
        $display("Output 1 = %b" , OUT1); 
        $display("Output 2 = %b\n" , OUT2);
        
        #50
        $display("@50"); 
        $display("Output 1 = %b" , OUT1); 
        $display("Output 2 = %b\n" , OUT2); 
        
        #60
        $display("@60"); 
        $display("Output 1 = %b" , OUT1); 
        $display("Output 2 = %b\n" , OUT2); 
        
        
        $finish;
    end
    
    always #1 CLK=~CLK;     // Clock is 2
endmodule


module regFile(regIn, regOutA, regOutB, regAddrIn, regAddrA, regAddrB, CLK, RESET, WRITE_EN);
    
    input CLK, RESET, WRITE_EN;			// Control inputs
    input [2:0] regAddrIn;				// Input address
    input [2:0] regAddrA, regAddrB;		// Output addresses
    input [7:0] regIn;					// Input value 
  
    output [7:0] regOutA, regOutB;		// Output values

    reg [7:0] A, B;
    reg [7:0] registers[7:0];

	integer i;
	wire writeEnable;	// Only writes to the register when WRITE_EN=1
	and(writeEnable, CLK, WRITE_EN);

	// -- Inputs -----------------------------------------------
    always @(posedge writeEnable) begin
        registers[regAddrIn] <= regIn;
    end

	// -- Outputs ----------------------------------------------
    always @(negedge CLK) begin
        A <= registers[regAddrA];
        B <= registers[regAddrB];
    end

    assign regOutA = A;
    assign regOutB = B;
    
	// -- Reset -----------------------------------------------
    always @(posedge RESET) begin		   
        $display("Register reset"); 
        for(i=0;i<8;i=i+1) begin
    	    registers[i] <= 8'b00000000;
    	end
	end
	
endmodule
