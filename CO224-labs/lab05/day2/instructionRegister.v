
module main;

    wire [31:0] inst;
    reg [7:0] instAddr;
    reg CLK = 1'b1;
   
    instReg myInstructions(inst, instAddr, CLK);

    initial begin
        #0
        instAddr <= 8'b00000000;
      
        #60
        $display("@60"); 
        $display("Instruction: %b", inst); 
        
        $finish;
    end
    
    always #1 CLK=~CLK;     // Clock is 2
endmodule


module instReg(inst, instAddr, CLK);
    // 32-bit instruction register of 256 instructions
	
    input CLK;						// Control inputs
    input [7:0] instAddr;		// Instruction address
    output [31:0] inst;			// Instruction values
    reg [255:0] memory[31:0];	// Storage
	//reg [31:0] temp;
	reg [31:0] currInst;
	
	integer i;

    initial begin
    	memory[0] <= 32'b00000000000000000000000000000000 ;
		memory[1] <= 32'b00000000000000000000000000000000 ;
		memory[2] <= 32'b00000000000000000000000000000000 ;
		memory[3] <= 32'b00000000000000000000000000000000 ;
    end
	
    always @(posedge CLK) begin
        currInst <= memory[instAddr];
    end
	
    assign inst = currInst;
	
endmodule
