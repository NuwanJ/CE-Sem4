
module main;

    reg [7:0] A,B;
    reg [2:0] sel;
	output [7:0] R;
	
	ALU myALU(R, A,B, sel);
	
	initial begin
		A <= 8'b00000111;
		B <= 8'b00000011;
		
		sel <= 2;
		// 0:FORWARD 1:ADD 2:AND 3:OR
	
		#1 $display("A= %d B =%d ===( %d )===> Output =%d \n", A, B, sel, R);
	end
	
endmodule

module ALU(result, dataA, dataB, select);

    input [7:0] dataA,dataB;    // Input data paths
	input [2:0] select;         // ALU operation    0:Forward, 1:Add, 2:AND, 3:OR, 4-7:N/A
	output [7:0] result;
	
    reg [7:0] R;

    always @(select) begin
		case(select)
                0: R = dataA; 		    // FORWARD
            1: R = dataA + dataB;	// ADD
            2: R = dataA & dataB;	// AND 
            3: R = dataA | dataB;	// OR
            default: R = 0;
        endcase
    end
    
	assign result = R;
	
endmodule
