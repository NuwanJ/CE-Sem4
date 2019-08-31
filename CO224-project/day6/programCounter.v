module main;

    reg [7:0] PC;   
    reg CLK, RESET;
	reg BUSY;
    wire [7:0] OUT;

	programCounter myPC(OUT, CLK, RESET, BUSY);
	
	initial begin
	    #0
	    RESET <= 0;
	 	CLK <= 0;   
		BUSY <= 0;
	 	PC <= 8'b00000000;
	 	
	    #2
	    RESET <= 1;

        #4
        RESET <= 0;
        
        #8
        //RESET <= 0;
	    //$display("PC=%d", OUT);
		
		#10
		BUSY <= 1;
		
		#14
		BUSY <= 0;
		
		#20
		$finish;
    end

    always begin
        #1 CLK = ~CLK;
    end 
    
	always begin 
		#2
		$display("PC=%d, %b", OUT, CLK);
    end
	
endmodule

module programCounter(PC, CLK, RESET, BUSY);
	// 8-bit program counter
	
	input CLK, RESET, BUSY;
	output [7:0] PC;    
	
    reg [7:0] pc;
    
    always @(negedge CLK) begin
		// PC increases on negative edge
		if (BUSY==0) begin
			pc <= (pc + 8'b00000001);
		end
	end
	
	always @(posedge RESET) begin
		// reseting the PC value to zero
		pc <= 0;
	end
	
	initial begin
		// inisital value for PC is zero 
	    pc <= 0;
	end
	
	assign PC = pc;
	
endmodule
