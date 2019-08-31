module main;

    reg [7:0] PC;   
    reg CLK, RESET;

    wire [7:0] OUT;

	programCounter myPC(OUT, PC, CLK, RESET);
	
	initial begin
	    #0
	    RESET <= 0;
	 	CLK <= 0;   
	 	PC <= 8'b00000000;
	 	
	    #2
	    RESET <= 1;

        #8
        RESET <= 0;
	    $display("PC=%d", OUT);
		
		#12 $display("PC=%d", OUT);
		
		$finish;
    end

    always #2 CLK = ~CLK;
    always #4 PC = PC + 4;
    
endmodule

module programCounter(OUT, IN, CLK, RESET);
	// 8-bit program counter
	
	input [7:0] IN;        // next PC value
	input CLK, RESET;
	output [7:0] OUT;      // PC value (output)
	
    reg [7:0] pc;
    
	// REM: use pos or neg edge
    always @(posedge CLK) begin
		assign pc = IN;
	end
	
	always @(posedge RESET) begin
	    //$display("Program Counter Reset");
		assign pc = 8'b00000000;
	end
	
	assign OUT = pc;
	
endmodule
