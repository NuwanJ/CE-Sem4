module main;

    reg [7:0] PC;   
    reg CLK, RESET;

    wire [7:0] OUT;

	programCounter myPC(OUT, CLK, RESET);
	
	initial begin
	    #0
	    RESET <= 0;
	 	CLK <= 0;   
	 	PC <= 8'b00000000;
	 	
	    #2
	    RESET <= 1;

        #4
        RESET <= 0;
        
        #8
        //RESET <= 0;
	    //$display("PC=%d", OUT);
		
		#16
		$finish;
    end

    always #2 CLK = ~CLK;
    //always #4 PC = PC + 4;
    
endmodule

module programCounter(OUT, CLK, RESET);
	// 8-bit program counter
	
	input CLK, RESET;
	output [7:0] OUT;      // PC value (output)
	
    reg [7:0] pc;
    
	// REM: use pos or neg edge
    always @(posedge CLK) begin
		assign pc = (pc + 8'b00000001);
		//$display("PC = %b", pc); 
	end
	
	always @(posedge RESET) begin
		assign pc = 8'b00000000;
		//$display("PC Reset"); 
	end
	
	assign OUT = pc;
	
endmodule
