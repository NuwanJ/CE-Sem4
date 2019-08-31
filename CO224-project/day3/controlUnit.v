
// Note: incomplete module

module main;

    reg [7:0] PC;   
    reg CLK, RESET;

    wire [7:0] OUT;

	controlUnit myControl(OUT, PC, CLK, RESET);
	
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

module controlUnit(pc,aluSelect,dataOneI, outOneAddr,outTwoAddr,inAddr, aSelect,bSelect,iSelect, ins);
	
	input [31:0] ins;      		 // instruction
	
	output [7:0] pc;      			// program counter
	output [2:0] aluSelect;	// select ALU operation
	
	output [7:0] dataOneI; 	// dataOne Immediate Value
	output [2:0] outOneAddr, outTwoAddr, inAddr;
	output aSelect, bSelect, iSelect;
	
    reg [7:0] PC;					// pc register
	reg [31:0] IR;					// instruction register
    
    always @(posedge CLK) begin
		assign IR = ins;
		assign PC = PC + 4;
	end
	
	always @(posedge RESET) begin
	    //$display("Program Counter Reset");
		assign PC = 8'b00000000;
	end
	
	assign pc = PC;
	
endmodule
